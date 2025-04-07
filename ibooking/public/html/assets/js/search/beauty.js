(function ($) {
    'use strict';

    var GmzSearchBeauty = {
        container: '',
        xhrRequest: null,
        dataFilter: [],
        currentURL: window.location,
        mapObject: undefined,
        checkHover: -1,
        allPopups: [],
        isReload: true,
        loader: '',
        renderEl: '',
        bounds: (typeof mapboxgl == 'object') ? new mapboxgl.LngLatBounds() : {},

        init: function (el) {
            this.container = $(el);
            this.dataFilter = this._urlToArray();
            this.loader = $('.gmz-page-loader');
            this.renderEl = $('.list-beauty__content', this.container);
            this.searchStringEl = $('.results-count', this.container);
            this._initEventSearch();
            this._initSearch();
        },

        _initEventSearch: function(){
            var base = this;

            base.container.on('change', '.sort-item input', function (ev) {
                ev.preventDefault();
                var sort = $(this).val();
                base.dataFilter['sort'] = sort;
                base.dataFilter['page'] = 1;
                base._initSearch();
                base._pushStateToFilter('sort', sort);
                base._pushStateToFilter('page', 1);
            });

            base.container.on('click', '.layout-item', function (ev) {
                ev.preventDefault();
                if (!$(this).hasClass('active')) {
                    $(this).parent().find('a').removeClass('active');
                    $(this).addClass('active');
                    var layout = $(this).data('layout');
                    base.dataFilter['layout'] = layout;
                    base.dataFilter['page'] = 1;
                    base._initSearch();
                    base._pushStateToFilter('layout', layout);
                    base._pushStateToFilter('page', 1);
                }
            });

            base.container.on('click', '.gmz-load-more button:not(.disabled)', function (ev) {
                ev.preventDefault();
                base.dataFilter['page'] = $(this).data('page');
                base._initSearch();
            });

            $('[name="price_range"]', base.container).on('gmz_range_changed', function () {
                var value = $(this).val();
                if (base.dataFilter['price_range'] !== value) {
                    base.dataFilter['price_range'] = value;
                    base._resetPage();
                    base._initSearch();
                    base._pushStateToFilter('price_range', value);
                }
            });

            $('.gmz-checkbox-wrapper input[type="hidden"]', base.container).on('change', function () {
                var t = $(this);
                base.dataFilter[t.attr('name')] = t.val();
                base._resetPage();
                base._initSearch();
                base._pushStateToFilter(t.attr('name'),  t.val());
            });
        },

        _initSearch: function(){
            var base = this;
            if (base.xhrRequest != null) {
                base.xhrRequest.abort();
            }

            base.dataFilter['_token'] = $('meta[name="csrf-token"]').attr('content');

            if(!base.isReload){
                base.loader.fadeIn('fast');
            }

            base.xhrRequest = $.post(base.container.data('action'), base.dataFilter, function (respon) {
                if (typeof respon == 'object') {
                    if(respon.current_page == 1){
                        base.renderEl.html((respon.search_string + respon.html));
                        base.renderEl.append('<div class="gmz-load-more">'+ respon.pagination +'</div>');
                    }else {
                        $((respon.search_string + respon.html)).insertBefore('.gmz-load-more');
                        base.renderEl.find('.gmz-load-more').html(respon.pagination);
                    }
                }

                //Render map
                if(respon['map'].length){
                    if (typeof mapboxgl == 'object' && gmz_params.mapbox_token !== '') {
                        mapboxgl.accessToken = gmz_params.mapbox_token;
                        var centerLocation = [respon['map'][0].lng, respon['map'][0].lat];

                        $('.gmz-mapbox', base.container).each(function () {
                            if (typeof base.mapObject == 'undefined') {
                                var t = $(this),
                                    zoom = 13;

                                base.mapObject = new mapboxgl.Map({
                                    container: t.get(0),
                                    style: 'mapbox://styles/mapbox/streets-v11',
                                    center: centerLocation,
                                    zoom: zoom
                                });
                                var map = base.mapObject;
                                map.addControl(new mapboxgl.NavigationControl({showCompass: false}), 'bottom-right');
                                map.scrollZoom.disable();
                                map.scrollZoom.setWheelZoomRate(0.02);
                                map.on("wheel", event => {
                                    if (event.originalEvent.ctrlKey) {
                                        event.originalEvent.preventDefault();
                                        if (!map.scrollZoom._enabled) map.scrollZoom.enable();
                                    } else {
                                        if (map.scrollZoom._enabled) map.scrollZoom.disable();
                                    }
                                });
                                map.dragRotate.disable();
                                map.touchZoomRotate.disableRotation();
                            }

                            respon['map'].forEach(function (item) {
                                if ($('.gmz-map-price[data-id="' + item.id + '"]', base.container).length === 0) {
                                    var popup = new mapboxgl.Popup({
                                        closeOnClick: false,
                                        closeButton: false
                                    }).setLngLat([item.lng, item.lat])
                                        .setHTML('<div class="gmz-map-popup">' +
                                            '</div><div class="gmz-map-price" data-id="' + item.id + '"><span class="price-innner">' + item.price + '</span></div>')
                                        .addTo(base.mapObject);
                                    base.allPopups.push(popup);
                                }
                                base.bounds.extend([item.lng, item.lat]);
                            });

                            base.mapObject.fitBounds(base.bounds, {
                                padding: 70
                            });
                        });
                    }
                }

                base.loader.fadeOut('fast');
                if($('[data-toggle="tooltip"]').length) {
                    $('.tooltip').hide();
                    $('[data-toggle="tooltip"]').tooltip('update');
                }
                /*Check image loaded*/
                var parentImage = $('.list-half-map__left');
                if($('.list-half-map__left .list-beauty__content').length) {
                    $('.list-beauty__content', parentImage).find('img').one("load", function () {
                        $(this).addClass('loaded');
                        if ($('.list-beauty__content', parentImage).find('img.loaded').length === $('.list-beauty__content', parentImage).find('img').length) {
                            setTimeout(function () {
                                if($('[data-plugin="nicescroll"]').length) {
                                    $('[data-plugin="nicescroll"]').getNiceScroll().remove();
                                    $('[data-plugin="nicescroll"]').niceScroll();
                                    $('[data-plugin="nicescroll"]').getNiceScroll().resize();
                                    if ($('[data-plugin="matchHeight"]').length) {
                                        $('[data-plugin="matchHeight"]').matchHeight();
                                    }
                                }
                            }, 100);
                        }
                    });
                }
            }, 'json');
            base.isReload = false;
        },

        _urlToArray: function () {
            var base = this;
            var res = {};

            if ($('.pagination', base.container).length) {
                var pagination = parseInt($('.page-item.active .page-link', base.container).text());
                res['page'] = pagination <= 0 ? 1 : pagination;
            } else {
                res['page'] = 1;
            }

            var sPageURL = window.location.search.substring(1);
            if (sPageURL !== '') {
                var sURLVariables = sPageURL.split('&');
                if (sURLVariables.length) {
                    for (var i = 0; i < sURLVariables.length; i++) {
                        var sParameterName = sURLVariables[i].split('=');
                        if (sParameterName.length) {
                            var val = decodeURIComponent(sParameterName[1]);
                            res[decodeURIComponent(sParameterName[0])] = (val === 'undefined') ? '' : val;
                        }
                    }
                }
            }

            return res;
        },

        _pushStateToFilter: function (key, value, del) {
            var base = this;
            var url = new URL(base.currentURL);
            var query_string = url.search;
            var search_params = new URLSearchParams(query_string);

            if (del) {
                if (search_params.has(key)) {
                    search_params.devare(key);
                }
            } else {
                if (search_params.has(key)) {
                    search_params.set(key, value);
                } else {
                    search_params.append(key, value);
                }
            }

            url.search = search_params.toString();
            base.currentURL = url.toString();
            window.history.pushState({path: base.currentURL}, '', base.currentURL);
        },

        _getUrlParameter: function(url, key) {
            var sPageURL = url.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === key) {
                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
        },

        _resetPage: function () {
            var base = this;
            base.dataFilter['page'] = 1;
            base._pushStateToFilter('page', 1);
        },
    };

    $(document).ready(function() {
        GmzSearchBeauty.init('.gmz-search-result');
    });
})(jQuery);