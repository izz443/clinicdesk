<?php
class Paginator {
    private $totalItems;
    private $perPage;
    private $currentPage;

    public function __construct($totalItems, $perPage = ITEMS_PER_PAGE, $currentPage = 1) {
        $this->totalItems = (int)$totalItems;
        $this->perPage = (int)$perPage;
        $this->currentPage = (int)$currentPage > 0 ? (int)$currentPage : 1;
    }

    // حساب إجمالي عدد الصفحات الناتجة
    public function totalPages() {
        return (int)ceil($this->totalItems / $this->perPage);
    }

    // حساب قيمة الإزاحة الحالية OFFSET في قاعدة البيانات
    public function offset() {
        return ($this->currentPage - 1) * $this->perPage;
    }

    // هل توجد صفحة سابقة؟
    public function hasPrev() {
        return $this->currentPage > 1;
    }

    // هل توجد صفحة تالية؟
    public function hasNext() {
        return $this->currentPage < $this->totalPages();
    }

    // جلب رقم الصفحة الحالية
    public function getCurrentPage() {
        return $this->currentPage;
    }
}
