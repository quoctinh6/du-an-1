<?php
class AdminCtrl
{
    public function index()
    {
        include_once 'Views/admin/admin.php';
    }

    public function account()
    {
        include_once 'Views/admin/admin_account.php';
    }

    public function products()
    {
        include_once 'Views/admin/admin_products.php';
    }

    public function orders()
    {
        include_once 'Views/admin/admin_orders.php';
    }

    public function categories()
    {
        include_once 'Views/admin/admin_category.php';
    }

    public function variants()
    {
        include_once 'Views/admin/admin_variants.php';
    }

    public function user()
    {
        include_once 'Views/admin/user_profile.php';
    }
}
?>