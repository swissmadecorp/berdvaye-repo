<nav class="col-sm-3 col-md-2 hidden-xs-down bg-faded sidebar">
    @role('user|superadmin')
    <ul class="nav nav-pills flex-column">
        <li class="nav-item">
            <a class="nav-link {{ (Request::is('admin') ? 'active' : '') }}" href="<?= URL::to('/') ?>/admin">Overview<span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (Request::is('admin/products') || Request::is('adin/products/*') ? 'active' : '') }}" href="<?= URL::to('/') ?>/admin/products">Products</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (Request::is('admin/productretail') || Request::is('adin/productretail/*') ? 'active' : '') }}" href="<?= URL::to('/') ?>/admin/productretail">Product Prices</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (Request::is('admin/customers') || Request::is('admin/customers/*') ? 'active' : '') }}" href="<?= URL::to('/') ?>/admin/customers">Customers</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (Request::is('admin/orders') || Request::is('admin/orders/*') ? 'active' : '') }}" href="<?= URL::to('/') ?>/admin/orders">Invoices</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (Request::is('admin/estimates') || Request::is('admin/estimates/*') ? 'active' : '') }}" href="<?= URL::to('/') ?>/admin/estimates">Orders</a>
        </li>        
        <li class="nav-item">
            <a class="nav-link {{ (Request::is('admin/returns') ? 'active' : '') }}" href="<?= URL::to('/') ?>/admin/returns">Returns</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (Request::is('admin/credits') ? 'active' : '') }}" href="<?= URL::to('/') ?>/admin/credits">Payments/Credits</a>
        </li>        
    </ul>

    <hr>
    <ul class="nav nav-pills flex-column">
        <li class="nav-item">
            <a class="nav-link {{ (Request::is('admin/reports') ? 'active' : '') }}" href="<?= URL::to('/') ?>/admin/reports">Reports</a>
        </li>
        <!-- <li class="nav-item">
            <a class="nav-link {{ (Request::is('admin/imagecollections') ? 'active' : '') }}" href="<?= URL::to('/') ?>/admin/imagecollections">Images</a>
        </li> -->
    </ul>
    <hr>
    <ul class="nav nav-pills flex-column">
        <!-- <li class="nav-item">
            <a class="nav-link {{ (Request::is('admin/posts') ? 'active' : '') }}" href="<?= URL::to('/') ?>/admin/posts">Posts</a>
        </li> -->
        <li class="nav-item">
            <a class="nav-link {{ (Request::is('admin/dealers') ? 'active' : '') }}" href="<?= URL::to('/') ?>/admin/dealers">Dealers</a>
        </li>
    </ul>
    @role('superadmin')
    <hr>
    <ul class="nav nav-pills flex-column">
        <li class="nav-item">
            <a class="nav-link {{ (Request::is('admin/users') ? 'active' : '') }}" href="<?= URL::to('/') ?>/admin/users">Users</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (Request::is('admin/roles') ? 'active' : '') }}" href="<?= URL::to('/') ?>/admin/roles">Roles</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (Request::is('admin/permissions') ? 'active' : '') }}" href="<?= URL::to('/') ?>/admin/permissions">Permissions</a>
        </li>
    </ul>
    @endrole
    <hr>
    @endrole

    <ul class="nav nav-pills flex-column" style="display: none">
        <li class="nav-item">
            <a class="nav-link {{ (Request::is('admin/sculptureorders') ? 'active' : '') }}" href="<?= URL::to('/') ?>/admin/sculptureorders">Sculpture Invoices</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (Request::is('admin/sculpturerepair') ? 'active' : '') }}" href="<?= URL::to('/') ?>/admin/sculpturerepair">Sculpture Repairs</a>
        </li>
    </ul>
    <hr>
</nav>