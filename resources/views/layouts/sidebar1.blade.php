<div class="list-group">
    
    <div class="list-group-list">
    <ul>

        @foreach ($categories as $category)
            <?php $totalItems = count($category->products) ?>
            @if ($totalItems)
            <?php $cat = URL::to('/watches').'/'.$category->id.'/'.strtolower($category->category_name) ?>
            <?php if (strpos($cat,' ') !==false) {
                $cat = str_replace(' ','-',$cat);
            } ?>

            <li class="ui-menu-item level0">
                <div class="open-children-toggle"></div>
                <a href="<?php echo $cat ?>/" class="level-top">
                    <span>{{ $category->category_name }}</span>
                    <span>{{ ' ('.$totalItems.')' }}</span>
                </a>
            </li>
            @else
            <li class="ui-menu-item level0">
                <div class="open-children-toggle"></div>
                <p class="level-top">
                    <span>{{ $category->category_name }}</span>
                    <span>(0)</span>
                </p>
            </li>
            @endif
        @endforeach
        
    </ul>
    </div>

    @if (isset($products))
    <div id="product-filters">
        <div class="list-group-item">Condition</div>
        <div class="list-group-list _condition">
            <ul>
                @foreach (Conditions()->take(4)->splice(1)->all() as $condition)
                    <li class="filter">
                        <a href="javascript:void(0)" data-filter="condition={{ $condition }}">{{ $condition }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="list-group-item">Status</div>
        <div class="list-group-list _status">
            <ul>
                @foreach (Status() as $status)
                    <li class="filter">
                        <a href="javascript:void(0)" data-filter="status={{ $status }}">{{ $status }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="list-group-item">Price</div>
        <div class="list-group-list">
            <div class="price">
                <div class="col">
                    From <input data-filter="pfrom" type="text" style="width: 100%" name="pfrom">
                </div>
                <div class="col">
                    To <input data-filter="pto" type="text" style="width: 100%" name="pto">
                </div>
                <div class="col">
                    <button type="submit" style="width:100%;margin:13px 0" class="btn btn-primary uploadPhoto">Search</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@section ('jquery')
<script>
    $(document).ready( function() {
        var ms='',vars = [];
        
        ms = getUrlVars();
        function getUrlVars(rep,elem) {
            var hash='',b_found=-1;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

            if (rep == 'clear'){
                var indx='';
                
                $.each (hashes, function (i,v) {
                    if (v.indexOf('p=')>-1){
                        indx = v;
                    }
                })

                url = window.location.href.split('?')[0];
                return url;
            }
                
            if (hashes[0].indexOf('http')!=-1) {
                hashes.splice(0,1);
            }

            if (elem != undefined) {
                str = elem.parent('li').attr('data-original');
                f = $.inArray(str,hashes);
                if (f != -1)
                    hashes.splice(f,1);
                
            }

            if (rep != undefined) {    
                $.each (hashes, function (i,v) {
                    test1 = hashes[i].split('=')[0];
                    test2 = rep.split('=')[0];
                    if (test1 == test2) {
                        b_found = i;
                    }
                })

                if (b_found != -1)
                    hashes[b_found] = rep;
                else {
                    hashes.push(rep);
                }
                    

                $.each (hashes, function (i,v) {
                    hash += v+'&';
                })

                return '?'+hash.substr(0,hash.length-1);
            
            } else {
                if (typeof hashes[0] != 'undefined') {
                   
                    $('#filter-group ul').html('');
                    $.each (hashes, function (i,v) {
                        hash += v+'&';
                        if (v.indexOf('p=')>-1) {
                            $('#filter-group ul').prepend("<li data-original='"+v+"'>"+strRep(v)+"</li>");
                        } else if (v.indexOf('status=')>-1) {
                            $('#filter-group ul').prepend("<li data-original='"+v+"'>"+strRep(v.replace('_'," "))+"<button class='remove_filter'><i class='fa fa-times' aria-hidden='true'></i></button></li>");
                        } else if (v.indexOf('page=')==-1) 
                            $('#filter-group ul').prepend("<li data-original='"+v+"'>"+strRep(v)+"<button class='remove_filter'><i class='fa fa-times' aria-hidden='true'></i></button></li>");
                    })

                    if ($('.clear-all').length == 0 && hashes.length>1)
                        $('.filter-list').append("<a class='btn btn-primary clear-all' style='padding: 4px;' href=''>Clear Filters</a>");

                    if (elem != undefined) {
                        return '?'+hash.substr(0,hash.length-1);
                    }

                } else {
                    return window.location.href.split('?')[0];
                }
            }
        }

        function strRep(str) {
            spl = str.split("=");
            
            spl1 = spl[0].charAt(0).toUpperCase() + spl[0].slice(1);
            spl2 = spl[1].charAt(0).toUpperCase() + spl[1].slice(1);

            if (spl1 == 'P')
                spl1 = 'Search';

            return spl1+': '+spl2;
        }

        $('._condition a').click ( function () {
            ms = getUrlVars($(this).attr('data-filter'));
            document.location.href = ms.replace(' ','-').toLowerCase();
        })

        $('._status a').click ( function (e) {
            e.preventDefault();
            ms = getUrlVars($(this).attr('data-filter'));
            document.location.href = ms.replace(' ','-').toLowerCase();
        })

        $('.clear-all').click ( function (e) {
            e.preventDefault();
            ms = getUrlVars('clear');
            document.location.href = ms.toLowerCase();
        })

        $('.remove_filter').click( function () {
            ms = getUrlVars($(this).attr('data-filter'), $(this));
            document.location.href = ms.toLowerCase();
        })

        $('.price button').click( function (e) {
            e.preventDefault();
            builder = 'price='+$('input[name="pfrom"]').val()+'-'+$('input[name="pto"]').val();
            ms = getUrlVars(builder);
            
            document.location.href = ms.toLowerCase();
        })

        var slideLeft = new Menu({
            wrapper: '#o-wrapper',
            type: 'push-left',
            menuOpenerClass: '.c-button',
            maskId: '#c-mask'
        });

        $('#c-button--push-left').click(function(e) {
            e.preventDefault;
            slideLeft.open();
        });

        navigation();

        $( window ).resize(function() {
            navigation();
        })

        function navigation() {
            var is_touch_device = 'ontouchstart' in document.documentElement;
            var viewport = $(window).width()
            if (viewport < 768) {

                if ($('.navigation').parents().length!=2) {
                    $('body').append($('.navigation'));
                    $('.c-menu__close').show();
                    $('.togge-menu').height($(window).height()-50)
                }
            } else {
                if ($('.navigation').parents().length!=7) {
                    $('.custom-menu').append($('.navigation'));
                    $('.c-menu__close').hide();
                }
            }

            if ($('.c-button').css('display') == "inline-block") {
                $('body').append($('.navigation'));
                $('.c-menu__close').show();
            }
            
        }

    })    
</script>
@endsection        