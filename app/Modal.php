<?php 
namespace app;
class Modal{
    public $table;
    protected $pdo;
    protected function getSearchQuery()
    {
        return isset($_GET["search"]) ? $_GET["search"] : '';
    }

    protected function getCurrentPage()
    {
        return isset($_GET["page"]) ? (int)$_GET["page"] : 1;
    }

    protected function calculateOffset($page, $limit)
    {
        return $page>1?($page - 1) * $limit:1;
    }

    protected function getTotalRecords($query)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";

        if (!empty($query)) {
            $sql .= " WHERE title LIKE :query OR content LIKE :query";
        }

        $stmt = $this->pdo->prepare($sql);

        if (!empty($query)) {
            $stmt->bindValue(':query', '%' . $query . '%', \PDO::PARAM_STR);
        }

        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result['total'];
    }

    protected function validatePageNumber($page, $totalPages, $query)
    {
        if ($page > $totalPages) {
            $page = $totalPages;

            // Redirect to the valid page
            $_GET["page"] = $page;
            header("Location: ?page=$page" . (!empty($query) ? "&search=" . urlencode($query) : ""));
            exit;
        }

        return $page;
    }

    public function getAllCount(){
        $sql="SELECT COUNT(*) as total FROM $this->table";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return  $data['total'];
    }

    public function find($id){
        $sql="SELECT * from $this->table WHERE id=".$id;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data?$data:false;
    }
    
    protected function getPaginatedData($query, $limit)
    {
        $page = $this->getCurrentPage();
        // Get total records and pages
        $totalRecords = $this->getTotalRecords($query);
        $totalPages = ceil($totalRecords / $limit);

        // Adjust page number if it exceeds total pages
        $page = $this->validatePageNumber($page, $totalPages, $query);
        $offset = $this->calculateOffset($page, $limit);

        $sql = "SELECT * FROM {$this->table}";

        if (!empty($query)) {
            $sql .= " WHERE title LIKE :query OR content LIKE :query";
        }

        $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);

        if (!empty($query)) {
            $stmt->bindValue(':query', '%' . $query . '%', \PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        // Store pagination data in the session
        $this->storePaginationData($page, $totalPages);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function storePaginationData($page, $totalPages)
    {
        $_SESSION['current_page'] = $page;
        $_SESSION['total_page'] = $totalPages;
    }
}