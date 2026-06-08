
    $.fn.extend( {
        dropdown: function (options) {
            
            var defaults={
                startFrom: 3,
                getPath: '',
                mainPath: '',
                parent: '.billing',
                container: '#search-customer',
                searchBy: 'firstname',
                singleItem: '.customer-item',
                success: function() {}
            }

            var options = $.extend(defaults, options);
            var _this = $(this);
            var activeDiv;
            var selectedItem = 0; prevSelectedItem = -1;
            var reset = -1;

            $(this).focus( function (e) {
                e.stopPropagation();
                if ($(this).val().length == options.startFrom) {
                    $(options.container).fadeIn(300);
                }
            })

            $(this).on('keydown input', function (e) {
                if (e.keyCode == 40 && $(options.container).is(":visible") && e.type=='keydown') {
                    activeDiv = $(options.container).children(':first');

                    activeDiv.attr('tabindex',1);
                    activeDiv.addClass('active');
                    activeDiv.focus();
                    selectedItem = 0;
                }
                
                if ((e.which == 27 || e.which == 9) && $(options.container).is(":visible")) {
                    $(_this).focus();
                    $(options.container).fadeOut(300);
                    return
                }

                if (e.type=='keydown') return

                if ($(this).val().length == 0 && $(options.container).is(":visible")) {
                    $(options.container).hide();
                    return
                }

                if ($(this).val().length < options.startFrom) return

                $.ajax({
                    type: "GET",
                    url: options.mainPath,
                    data: { 
                        _token: csrf_token,
                        _criteria: $(_this).val(),
                        _searchBy: options.searchBy

                    },
                    success: function (result) {
                        if (result) {
                            $(options.container).css({
                                'left': $('div',options.parent).position().left+$('input',options.parent).position().left,
                                'top':$(options.parent).position().top+$('input',options.parent).outerHeight(),
                            })
                            
                            $(options.container).html(result);
                            $(options.container).show();
                        }
                    }
                })
            });

            $(document).on('click', options.singleItem, function() {
                selectItem($(this));
            })

            function selectItem(item) {
                $.ajax({
                    type: "GET",
                    url: options.getPath,
                    data: { 
                        _token: csrf_token,
                        _id: item.attr('data-id'),
                        _searchBy: options.searchBy
                    },
                    success: function (result) {
                        if (result) {
                            options.success(result);
                            $(_this).focus();
                            $(options.container).hide();
                        }
                    }
                })
            }

            $(document).click(function(e) {
                if ($(_this).attr('id') == e.target.id) return
                $(options.container).fadeOut(300);
            });

            $(options.container).on('keydown', function(e) {
                e.preventDefault();

                if (e.which == 27) {
                    $(_this).focus();
                    $(options.container).fadeOut(300);
                    return
                }
                
                if (e.which == 13) {
                    activeDiv = $(':eq("'+selectedItem+'")',options.container);
                    selectItem(activeDiv);
                }

                if (e.which == 40) {
                    if ($('div',options.container).length <= selectedItem+1) return 

                    prevSelectedItem = selectedItem;
                    selectedItem ++;
                } else {
                    if (selectedItem == 0) return 

                    prevSelectedItem = selectedItem;
                    selectedItem --;
                }

                activeDiv = $(':eq("'+selectedItem+'")',options.container);

                $(':eq("'+prevSelectedItem+'")',options.container).removeClass('active')
                
                activeDiv.attr('tabindex',1);
                activeDiv.addClass('active');
                activeDiv.focus();
            })
        }

    })
