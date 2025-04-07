<?php

namespace App\Modules\Backend\Controllers;

use TorMorten\Eventy\Facades\Events as Eventy;

class ScriptController
{
    static $_inst = null;
    private $_styles = [];
    private $_scripts = [];

    public function __construct()
    {
        $this->_enqueueScripts();
    }

    private function _enqueueScripts()
    {
        $this->_addStyle('google-fonts', 'https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap', true);
        $this->_addStyle('gmz-loader', asset('admin/assets/css/loader.css'), true);
        $this->_addStyle('bootstrap', asset('admin/bootstrap/css/bootstrap.min.css'), true);
        $this->_addStyle('gmz-plugin', asset('admin/assets/css/plugins.css'), true);
        $this->_addStyle('scrollspy-nav', asset('admin/assets/css/scrollspyNav.css'), true);
        //$this->_addStyle('fontawesome-regular', asset('admin/plugins/font-icons/fontawesome/css/regular.css'), true);
        //$this->_addStyle('fontawesome-solid', asset('admin/plugins/font-icons/fontawesome/css/solid.min.css'), true);
        //$this->_addStyle('fontawesome', asset('admin/plugins/font-icons/fontawesome/css/fontawesome.css'), true);

        $this->_addStyle('font-awesome', asset('html/assets/vendor/font-awesome-5/css/all.min.css'), true);

        $this->_addStyle('gmz-dash_1', asset('admin/assets/css/dashboard/dash_1.css'), true);
        $this->_addStyle('gmz-toast', asset('admin/plugins/toast/jquery.toast.min.css'), true);
        $this->_addStyle('gmz-toast1', asset('vendors/toastr/toastr.min.css'), true);

        $this->_addStyle('gmz-custom-tab', asset('admin/assets/css/components/tabs-accordian/custom-tabs.css'));
        $this->_addStyle('gmz-spectrum', asset('admin/plugins/spectrum/spectrum.css'));
        $this->_addStyle('gmz-quill', asset('admin/plugins/editors/quill/quill.snow.css'));
        $this->_addStyle('gmz-custom-accordions', asset('admin/assets/css/components/tabs-accordian/custom-accordions.css'));
        $this->_addStyle('gmz-checkbox', asset('admin/assets/css/forms/theme-checkbox-radio.css'));
        $this->_addStyle('gmz-dropzone', asset('admin/plugins/dropzone/dropzone.min.css'));
        $this->_addStyle('gmz-datatables', asset('admin/plugins/table/datatable/datatables.css'));
        $this->_addStyle('gmz-dt-global', asset('admin/plugins/table/datatable/dt-global_style.css'));
        $this->_addStyle('gmz-dt-multiple-tables', asset('admin/plugins/table/datatable/custom_dt_multiple_tables.css'));
        $this->_addStyle('gmz-steps', asset('admin/plugins/jquery-step/jquery.steps.css'));
        $this->_addStyle('gmz-switches', asset('admin/assets/css/forms/switches.css'));
        $this->_addStyle('flatpickr', asset('admin/plugins/flatpickr/flatpickr.css'));
        $this->_addStyle('custom-flatpickr', asset('admin/plugins/flatpickr/custom-flatpickr.css'));
        $this->_addStyle('perfect-scrollbar', asset('admin/plugins/perfect-scrollbar/perfect-scrollbar.css'));
        $this->_addStyle('bootstrap-select', asset('admin/plugins/bootstrap-select/bootstrap-select.min.css'));
        $this->_addStyle('select2', asset('admin/plugins/select2/select2.min.css'));

        $this->_addScript('gmz-jquery', asset('admin/assets/js/libs/jquery-3.1.1.min.js'), true);

        //jQuery ui
        $this->_addScript('jquery-ui', asset('vendors/jquery-ui/jquery-ui.min.js'));
        $this->_addStyle('jquery-ui', asset('vendors/jquery-ui/jquery-ui.min.css'));


        $this->_addScript('moment', asset('vendors/moment.min.js'), true);
        $this->_addScript('gmz-loader', asset('admin/assets/js/loader.js'), true);
        $this->_addScript('gmz-popper', asset('admin/bootstrap/js/popper.min.js'), true);
        $this->_addScript('gmz-bootstrap', asset('admin/bootstrap/js/bootstrap.min.js'), true);
        $this->_addScript('gmz-scrollbar', asset('admin/plugins/perfect-scrollbar/perfect-scrollbar.min.js'), true);
        $this->_addScript('gmz-app', asset('admin/assets/js/app.js'), true);
        $this->_addScript('gmz-custom', asset('admin/assets/js/custom.js'), true);
        $this->_addScript('gmz-scrollspy-nav', asset('admin/assets/js/scrollspyNav.js'), true);
        $this->_addScript('gmz-feather', asset('admin/plugins/font-icons/feather/feather.min.js'), true);
        $this->_addScript('gmz-validate', asset('js/bootstrap-validate.js'), true);
        $this->_addScript('gmz-toast', asset('admin/plugins/toast/jquery.toast.min.js'), true);
        $this->_addScript('gmz-toast1', asset('vendors/toastr/toastr.min.js'), true);
        $this->_addScript('gmz-dropzone', asset('admin/plugins/dropzone/dropzone.min.js'));
        $this->_addScript('gmz-datatables', asset('admin/plugins/table/datatable/datatables.js'));
        $this->_addScript('gmz-steps', asset('admin/plugins/jquery-step/jquery.steps.min.js'));
        $this->_addScript('gmz-quill', asset('admin/plugins/editors/quill/quill.js'));
        $this->_addScript('gmz-quill-image-resize', asset('vendors/image-resize.min.js'));
        $this->_addScript('gmz-spectrum', asset('admin/plugins/spectrum/spectrum.js'));
        $this->_addScript('flatpickr', asset('admin/plugins/flatpickr/flatpickr.js'));
        $this->_addScript('custom-flatpickr', asset('admin/plugins/flatpickr/custom-flatpickr.js'));
        $this->_addScript('perfect-scrollbar', asset('admin/plugins/perfect-scrollbar/perfect-scrollbar.min.js'));
        $this->_addScript('bootstrap-select', asset('admin/plugins/bootstrap-select/bootstrap-select.min.js'));
        $this->_addScript('select2', asset('admin/plugins/select2/select2.min.js'));

        //Auth
        $this->_addScript('gmz-form2', asset('admin/assets/js/authentication/form-2.js'));
        $this->_addStyle('gmz-form2', asset('admin/assets/css/authentication/form-2.css'));

        //Mapbox
        $this->_addScript('mapbox-gl', asset('vendors/mapbox/mapbox-gl.js'));
        $this->_addScript('mapbox-gl-geocoder', asset('vendors/mapbox/mapbox-gl-geocoder.js'));
        $this->_addStyle('mapbox-gl', asset('vendors/mapbox/mapbox-gl.css'));
        $this->_addStyle('mapbox-gl-geocoder', asset('vendors/mapbox/mapbox-gl-geocoder.css'));


        //Fullcalendar
        $this->_addStyle('gmz-calendar', asset('vendors/custom-price/css/calendar.css'));
        $this->_addScript('gmz-date', asset('vendors/date.js'));
        $this->_addStyle('fullcalendar', asset('vendors/fullcalendar/fullcalendar.min.css'));
        $this->_addStyle('fullcalendar-print', asset('vendors/fullcalendar/fullcalendar.print.min.css'));
        $this->_addScript('fullcalendar', asset('vendors/fullcalendar/fullcalendar.min.js'));
        $this->_addScript('gmz-calendar-init', asset('vendors/custom-price/js/init.js'));

        //Menu
        $this->_addScript('nested-sort-js', asset('vendors/jquery.mjs.nestedSortable.js'));

        //Flat Icon
        $this->_addStyle('flat-icon', asset('vendors/flag-icon/css/flag-icon.css'));

        //Option js
        $this->_addScript('gmz-option', asset('js/option.js'), true, GMZ_VERSION);


        //swal
        $this->_addStyle('sweetalerts', asset('vendors/sweetalerts/sweetalert.css'));
        $this->_addStyle('sweetalerts2', asset('vendors/sweetalerts/sweetalert2.min.css'));
        $this->_addScript('sweetalerts2', asset('vendors/sweetalerts/sweetalert2.min.js'));
        $this->_addScript('custom-sweetalerts', asset('vendors/sweetalerts/custom-sweetalert.js'));

        //widget
        $this->_addStyle('apexcharts', asset('vendors/apex/apexcharts.min.css'));
        $this->_addScript('apexcharts', asset('vendors/apex/apexcharts.min.js'));
        $this->_addStyle('modules-widgets', asset('admin/assets/css/widgets/modules-widgets.css'));
        $this->_addScript('gmz-widget', asset('js/widget.js'));

        //Footable
        $this->_addStyle('footable', asset('vendors/footable/css/footable.bootstrap.min.css'));
        $this->_addScript('footable', asset('vendors/footable/js/footable.min.js'));

        //Ace
        $this->_addScript('ace', asset('vendors/ace/ace.js'));

        //Custom js
        $this->_addScript('gmz-table', asset('js/gmz-table.js'));

        //Main admin css
        $this->_addScript('gmz-dashboard', asset('js/dashboard.js'), true, GMZ_VERSION);
        $this->_addStyle('gmz-wishlist', asset('css/wishlist.css'), true, GMZ_VERSION);
        $this->_addStyle('gmz-option', asset('css/option.css'), true, GMZ_VERSION);
        $this->_addStyle('gmz-dashboard', asset('css/dashboard.css'), true, GMZ_VERSION);


    }

    public function setQueueStyle($styles)
    {
        if (!empty($styles)) {
            if (is_array($styles)) {
                foreach ($styles as $item) {
                    if (isset($this->_styles[$item])) {
                        $this->_styles[$item]['queue'] = true;
                    }
                }
            } else {
                if (isset($this->_styles[$styles])) {
                    $this->_styles[$styles]['queue'] = true;
                }
            }
        }
    }

    public function setQueueScript($scripts)
    {
        if (!empty($scripts)) {
            if (is_array($scripts)) {
                foreach ($scripts as $item) {
                    if (isset($this->_scripts[$item])) {
                        $this->_scripts[$item]['queue'] = true;
                    }
                }
            } else {
                if (isset($this->_scripts[$scripts])) {
                    $this->_scripts[$scripts]['queue'] = true;
                }
            }
        }
    }

    public function initAdminHeader()
    {
        echo view('Backend::components.styles', ['styles' => $this->_styles]);
        Eventy::action('gmz_init_admin_header');
        ?>
        <script>
            var gmz_params = {
                mapbox_token: '<?php echo get_option('mapbox_token'); ?>',
                i18n: {
                    confirmText: '<?php echo __('Are you sure want to do it?'); ?>',
                    nextText: '<?php echo __('Next'); ?>',
                    previousText: '<?php echo __('Previous'); ?>',
                    finishText: '<?php echo __('Finish'); ?>'
                }
            }
        </script>
        <?php
    }

    public function initAdminFooter()
    {
        echo view('Backend::components.scripts', ['scripts' => $this->_scripts]);
        Eventy::action('gmz_init_admin_footer');
    }

    public function _addStyle($handle, $url, $queue = false, $v = '')
    {
        if (!isset($this->_styles[$handle])) {
            $this->_styles[$handle] = [
                'url' => $url,
                'queue' => $queue,
                'v' => $v
            ];
        }
    }

    public function _addScript($handle, $url, $queue = false, $v = '')
    {
        if (!isset($this->_scripts[$handle])) {
            $this->_scripts[$handle] = [
                'url' => $url,
                'queue' => $queue,
                'v' => $v
            ];
        }
    }

    public static function inst()
    {
        if (is_null(self::$_inst)) {
            self::$_inst = new self();
        }
        return self::$_inst;
    }
}