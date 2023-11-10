<script>
	var menus = {
		"oneThemeLocationNoMenus" : "",
		"moveUp" : "{{ trans('general.move_up') }}",
		"moveDown" : "{{ trans('general.move_down') }}",
		"moveToTop" : "{{ trans('general.move_top') }}",
		"moveUnder" : "{{ trans('general.move_under_of') }} %s",
		"moveOutFrom" : "{{ trans('general.out_from_under') }}  %s",
		"under" : "{{ trans('general.under') }} %s",
		"outFrom" : "{{ trans('general.out_from') }} %s",
		"menuFocus" : "%1$s. Element menu %2$d of %3$d.",
		"subMenuFocus" : "%1$s. Menu of subelement %2$d of %3$s."
	};
	var arraydata = [];     
	var addcustommenur= '{{ route("cat_add_custom_menu") }}';
	var updateitemr= '{{ route("cat_update_item")}}';
	var generatemenucontrolr= '{{ route("cat_gen_menu_control") }}';
	var deleteitemmenur= '{{ route("cat_delete_menu_item") }}';
	var deletemenugr= '{{ route("cat_delete_menu") }}';
	var createnewmenur= '{{ route("cat_create_new_menu") }}';
	var csrftoken="{{ csrf_token() }}";
	var menuwr = "{{ url()->current() }}";

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': csrftoken
		}
	});
</script>
<script type="text/javascript" src="{{asset('vendor/harimayco-menu/scripts.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/harimayco-menu/scripts2.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/harimayco-menu/menu.js')}}"></script>