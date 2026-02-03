@if (isset($paginator) )
<nav aria-label="Page navigation">
    <ul class="pagination">
        
        <?php
        $interval = isset($interval) ? abs(intval($interval)) : 3 ;
        $from = $paginator->currentPage() - $interval;
        if($from < 1){
            $from = 1;
        }
        
       
        
        $query='';$requests='';

        if (Request::all()) {
            foreach (Request::all() as  $key => $request) {
                if (strpos($key,'page') ===false )
                    $query .= '&'.$key . '=' . $request . '&';
            }

            $query = substr($query,0, strlen($query)-1);
       
        }
        ?>
        
        <!-- first/previous -->
        @if($paginator->currentPage() > 1)
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url(1).$query }}" aria-label="First">
                    <span aria-hidden="true">First</span>
                </a>
            </li>

            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url($paginator->currentPage() - 1).$query }}" aria-label="Previous">
                    <span aria-hidden="true">&lsaquo;</span>
                </a>
            </li>
        @endif
    
        
    </ul>
</nav>
@endif