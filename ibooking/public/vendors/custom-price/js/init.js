(function($){
    'use strict';

    if($('.date-picker').length) {
        $('.date-picker').flatpickr();
    }

    var CarCalendar = function(container){
        var self = this;
        this.container = container;
        this.calendar= null;
        this.form_container = null;
        this.params = null;

        this.init = function(){
            self.container = container;
            self.calendar = $('.calendar-content', self.container);
            self.form_container = $('.calendar-form', self.container);
            self.params = self.container.data('params');
            setCheckInOut('', '', self.form_container);
            self.initCalendar();
        };

        this.initCalendar = function(){
            self.calendar.fullCalendar({
                firstDay: 1,
                lang: self.params.locale,
                timezone: self.params.timezone,
                customButtons: {
                    reloadButton: {
                        text: self.params.text.refresh,
                        click: function() {
                            self.calendar.fullCalendar( 'refetchEvents' );
                        }
                    }
                },
                header : {
                    left:   'today,reloadButton',
                    center: 'title',
                    right:  'prev, next'
                },
                buttonText:{
                    today: self.params.text.today
                },
                selectable: true,
                select : function(start, end, jsEvent, view){
                    var start_date = new Date(start._d).toString("MM");
                    var end_date = new Date(end._d).toString("MM");
                    var start_year = new Date(start._d).toString("yyyy");
                    var end_year = new Date(end._d).toString("yyyy");
                    var today = new Date().toString("MM");
                    var today_year = new Date().toString("yyyy");
                    if((start_date < today && start_year <= today_year) || (end_date < today && end_year <= today_year)){
                        self.calendar.fullCalendar('unselect');
                        setCheckInOut('', '', self.form_container);
                    }else{
                        var zone = moment(start._d).format('Z');
                        zone = zone.split(':');
                        zone = "" + parseInt(zone[0]) + ":00";
                        var check_in = moment(start._d).utcOffset(zone).format("MM/DD/YYYY");
                        var	check_out = moment(end._d).utcOffset(zone).subtract(1, 'day').format("MM/DD/YYYY");
                        setCheckInOut(check_in, check_out, self.form_container);
                    }
                },
                events:function(start, end, timezone, callback) {
                    $.ajax({
                        url: self.container.data('action'),
                        dataType: 'json',
                        type:'post',
                        data: {
                            post_id:self.container.data('post-id'),
                            post_type:self.container.data('post-type'),
                            agent_service:self.container.data('agent-service'),
                            start: start.unix(),
                            end: end.unix(),
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(doc){
                            if(typeof doc === 'object'){
                                callback(doc);
                            }
                        },
                        error:function(e){
                            console.log(e);
                        }
                    });
                },
                eventClick: function(event, element, view){
                    setCheckInOut(event.start.format('MM/DD/YYYY'),event.start.format('MM/DD/YYYY'),self.form_container);
                    $('#calendar_price', self.form_container).val(event.price);
                    $('#calendar_status', self.form_container).val(event.status);
                },
                eventRender: function(event, element, view){
                    var html = '';
                    if(event.status == 'available'){
                        var classIsBase = 'is-base';
                        if(!event.is_base){
                            classIsBase = '';
                        }
                        if(typeof event.group_size !== 'undefined') {
                            html += '<div class="price available ' + classIsBase + '">' + self.params.text.adultPrice + ': ' + event.adult_price + '<br />' + self.params.text.childrenPrice + ': ' + event.children_price + '<br />' + self.params.text.infantPrice + ': ' + event.infant_price + '<br />' + self.params.text.groupSize + ': ' + event.group_size + '</div>';
                        }
                    }
                    if(typeof event.status !== 'undefined'){
                        if(event.status === 'available' && typeof event.group_size === 'undefined'){
                            html += '<div class="price available ' + classIsBase + '">' + self.params.text.price + ': ' + event.price + '</div>';
                        }
                        if(event.status === 'unavailable'){
                            html += '<div class="price unavailable">'+ self.params.text.unavailable +'</div>';
                        }
                        if(event.status === 'booked'){
                            html += '<div class="price booked">'+ self.params.text.booked +'</div>';
                        }
                        if(event.status === 'on-booking'){
                            html += '<div class="price on-booking">'+ self.params.text.onBooking +'</div>';
                        }
                        if(event.status === 'on-booking-price' ){
                            html += '<div class="price on-booking">'+ self.params.text.onBooking +'</div>';
                            if(typeof event.group_size === 'undefined'){
                                html += '<div class="price available ' + classIsBase + '">' + self.params.text.price + ': ' + event.price + '</div>';
                            }else{
                                html += '<div class="price available ' + classIsBase + '">' + self.params.text.adultPrice + ': ' + event.adult_price + '<br />' + self.params.text.childrenPrice + ': ' + event.children_price + '<br />' + self.params.text.infantPrice + ': ' + event.infant_price + '<br />' + self.params.text.groupSize + ': ' + event.group_size + '</div>';
                            }

                        }
                    }
                    if(typeof event.number !== 'undefined' && event.number !== '' && event.number > 0){
                        html += '<div class="number">'+ self.params.text.number + ': ' + event.number +'</div>';
                    }

                    //JOB
                    if(typeof event.time_slot !== 'undefined'){
                        if(event.time_slot !== '') {
                            html += '<div class="time" style="white-space: normal !important; color: #fff; padding: 7px 2px 2px 2px;"><i class="fal fa-clock"></i> ' + event.time_slot.join(', ') + '</div>';
                        }
                    }

                    $('.fc-content', element).html(html);
                },
                loading: function(isLoading, view){
                    var loader = $('.content > .gmz-loader');
                    if(isLoading){
                        loader.show();
                    }else{
                        loader.hide();
                    }
                },

            });
        }
    };

    function setCheckInOut(check_in, check_out, form_container){
        $('#calendar_check_in', form_container).val(check_in);
        $('#calendar_check_out', form_container).val(check_out);
    }
    function resetForm(form_container){
        $('#calendar_check_in', form_container).val('');
        $('#calendar_check_out', form_container).val('');
        $('#calendar_price', form_container).val('');
        $('#calendar_number_room', form_container).val('');
    }
    jQuery(document).ready(function($) {
        $('.calendar-wrapper').each(function(index, el) {
            var t = $(this);
            var car = new CarCalendar(t);

            var flag_submit = false;
            $('#calendar_submit', t).click(function(event) {
                var me = $(this);
                var data = $('input, select', '.calendar-form').serializeArray();
                var loader = $('.content > .gmz-loader');
                loader.show();
                data.push({
                    name: '_token',
                    value: $('meta[name="csrf-token"]').attr('content')
                });

                $('.form-message', t).attr('class', 'form-message').html('');
                if(flag_submit) return false; flag_submit = true;
                $.post(me.data('action'), data, function(respon, textStatus, xhr) {
                    if(typeof respon === 'object'){
                        if(respon.status){
                            resetForm(t);
                            $('.calendar-content', t).fullCalendar('refetchEvents');
                        }
                        $('.form-message', t).addClass(respon.type).html(respon.message).show();
                    }
                    flag_submit = false;
                    loader.hide();
                }, 'json');
                return false;
            });

            $(document).on('click','#circle-basic-t-3, #circle-basic-t-2',function(e){
                e.preventDefault();
                if(car.calendar == null){
                    car.init();
                }else{
                    car.calendar.fullCalendar( 'refetchEvents' );
                }
            });

            $('body').on('calendar.change_month', function(event, value){
                var date = car.calendar.fullCalendar('getDate');
                var month = date.format('M');
                date = date.add(value-month, 'M');
                car.calendar.fullCalendar( 'gotoDate', date.format('YYYY-MM-DD') );
            });
        });
    });
})(jQuery);
