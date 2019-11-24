<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminProcessingdetailsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = false;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "processingdetails";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"ProcessDate","name"=>"ProcessDate"];
			$this->col[] = ["label"=>"Waste Sub Type","name"=>"ID_PkWasteSCID","join"=>"masterwastesubcategories,SubCategoryName"];
			$this->col[] = ["label"=>"Category","name"=>"ID_FkCategoryID","join"=>"mastercategory,Category"];
			$this->col[] = ["label"=>"Pith","name"=>"ID_FkPithID","join"=>"masterpithdetails,Pith"];
			$this->col[] = ["label"=>"IntakeQty","name"=>"IntakeQty"];
			$this->col[] = ["label"=>"ProcessQty","name"=>"ProcessQty"];
			$this->col[] = ["label"=>"Status","name"=>"IsActive","callback_php"=>'($row->IsActive==1)?Active:InActive'];
			$this->col[] = ["label"=>"Added On","name"=>"ActionOn","callback_php"=>'date("d/m/Y h:i:s A",strtotime($row->ActionOn))'];
			$this->col[] = ["label"=>"Added By","name"=>"UserID","join"=>"cms_users,name"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Process Date','name'=>'ProcessDate','type'=>'date','validation'=>'required|date','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Waste Type','name'=>'ID_PkWasteID','type'=>'select2','width'=>'col-sm-8','datatable'=>'masterwastecategories,CategoryName'];
			$this->form[] = ['label'=>'Waste Sub Type','name'=>'ID_PkWasteSCID','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-6','datatable'=>'masterwastesubcategories,SubCategoryName'];
			$this->form[] = ['label'=>'Capacity','name'=>'Capacity','type'=>'text','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Category','name'=>'ID_FkCategoryID','type'=>'select2','width'=>'col-sm-6','datatable'=>'mastercategory,Category'];
			$this->form[] = ['label'=>'Unit','name'=>'ID_FkUnitID','type'=>'select2','width'=>'col-sm-6','datatable'=>'masterunitdetails,Units'];
			$this->form[] = ['label'=>'Pith','name'=>'ID_FkPithID','type'=>'select2','width'=>'col-sm-6','datatable'=>'masterpithdetails,Pith'];
			$this->form[] = ['label'=>'Intake Qty','name'=>'IntakeQty','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Stock','name'=>'Stock','type'=>'text','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Process Qty','name'=>'ProcessQty','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-8'];
			$this->form[] = ['label'=>'Net Balance','name'=>'NetBalance','type'=>'text','width'=>'col-sm-8'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Process Date','name'=>'ProcessDate','type'=>'date','validation'=>'required|date','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Waste Sub Category','name'=>'ID_PkWasteSCID','type'=>'select2','datatable'=>'masterwastecategories,CategoryName','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Category','name'=>'ID_FkCategoryID','type'=>'select2','datatable'=>'mastercategory,Category','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Pith','name'=>'ID_FkPithID','datatable'=>'masterpithdetails,Pith','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'IntakeQty','name'=>'IntakeQty','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'ProcessQty','name'=>'ProcessQty','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			# OLD END FORM

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
	        $this->addaction = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
	        $this->button_selected = array();

	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert        = array();
	                

	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array();



	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = array();     	          

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = "$(function() {
									$('#ProcessDate').datepicker().datepicker('setDate', 'today');
							  });";


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array(asset("js/process.js"));
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = "tfoot{display:none;}
						        #form-group-ID_FkCategoryID, #form-group-ID_FkUnitID{
							        /*float:left;*/
							    }
							    /* Small Devices, Tablets */
								@media only screen and (min-width : 768px) {
								 	/*#form-group-ID_FkUnitID{
									    width: 45%;
									    margin-left: 103px;
									}
									#form-group-ID_FkPithID{
										width: 50%;
    									float: right;
									}*/
								}
								/* Extra Small Devices, Phones */ 
								@media only screen and (max-width : 480px) {
								 #form-group-ID_FkUnitID{
									    width: 100%;
									}
									#form-group-ID_FkPithID{
										width: 100%;
									}
								}
						        ";
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
	        
	        
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
	        //Your code here
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
	        //Your code here
	        unset($postdata['ID_PkWasteID']);
	        unset($postdata['Stock']);
	        unset($postdata['Capacity']);
	        unset($postdata['ID_FkUnitID']);
	        unset($postdata['NetBalance']);
	    	$postdata['ProcessDate'] = date('Y-m-d', strtotime($postdata['ProcessDate']));
	    	$postdata['IsActive'] = 1;
	        $postdata['ActionOn'] = date('Y-m-d H:i:s');
	        $postdata['UserID'] = CRUDBooster::myId();
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here

	        unset($postdata['ID_PkWasteID']);
	        unset($postdata['Stock']);
	        unset($postdata['Capacity']);
	        unset($postdata['ID_FkUnitID']);
	        unset($postdata['NetBalance']);

	    	$postdata['ProcessDate'] = date('Y-m-d', strtotime($postdata['ProcessDate']));
	    	$postdata['IsActive'] = 1;
	        $postdata['ActionOn'] = date('Y-m-d H:i:s');
	        $postdata['UserID'] = CRUDBooster::myId();
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here
	    	DB::table('processingdetails')->where('id',$id)->update(['IsActive' => 0]);
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }



	    //By the way, you can still create your own method in here... :) 

	    public function getwastesubtype(){
	    	$wasteId = $_POST['wasteId'];
	    	$response = array();
	    	if (!empty($wasteId)) {
	    		$subType = DB::table('masterwastesubcategories')->select('id', 'SubCategoryName')->where('ID_FkWasteID',$wasteId)->where('IsActive', 1)->get();
	    	}
	    	echo json_encode($subType);exit;
	    }

	    public function getwastecapacity(){
	    	$wasteSubId = $_POST['wasteSubId'];
	    	$response = array();
	    	if (!empty($wasteSubId)) {
	    		$wasteCapacity = DB::table('masterwastesubcategories')->select('id', 'Capacity')->where('id',$wasteSubId)->where('IsActive', 1)->first();
	    	}
	    	echo json_encode($wasteCapacity);exit;
	    }

	    public function getwastecategory(){
	    	$wasteSubId = $_POST['wasteSubId'];
	    	$response = array();
	    	if (!empty($wasteSubId)) {
	    		$wasteCategory = DB::table('mastercategory')->select('id', 'Category')->where('ID_PkWasteSCID',$wasteSubId)->where('IsActive', 1)->get();
	    	}
	    	echo json_encode($wasteCategory);exit;
	    }

	    public function getunits(){
	    	$wasteSubId = $_POST['wasteSubId'];
	    	$response = array();
	    	if (!empty($wasteSubId)) {
	    		$Units = DB::table('masterunitdetails')->select('id', 'Units')->where('ID_PkWasteSCID',$wasteSubId)->where('IsActive', 1)->get();
	    	}
	    	echo json_encode($Units);exit;
	    }

	    public function getpreviousstock(){
	    	$stock = 0;$response = array();
	    	$wasteSubId = $_POST['wasteSubId'];
	    	if (!empty($wasteSubId)) {
		    	if ($wasteSubId == 4 || $wasteSubId == 5) { //Bio Culture and Bio Char
		    		$pithId = $_POST['pithId'];
		    		$unitId = $_POST['unitId'];
		    		$result = DB::table('processingdetails')->select(DB::raw('(IntakeQty - ProcessQty) as stock'))->where('ID_PkWasteSCID',$wasteSubId)->where('ID_FkPithID',$pithId)->where('IsActive', 1)->get();
		    	} elseif ($wasteSubId == 6 || $wasteSubId == 7) { //Category
		    		$categoryId = $_POST['categoryId'];
		    		$result = DB::table('processingdetails')->select(DB::raw('(IntakeQty - ProcessQty) as stock'))->where('ID_PkWasteSCID',$wasteSubId)->where('ID_FkCategoryID',$categoryId)->where('IsActive', 1)->get();
		    	} else { //other
		    		$result = DB::table('processingdetails')->select(DB::raw('(IntakeQty - ProcessQty) as stock'))->where('ID_PkWasteSCID',$wasteSubId)->where('IsActive', 1)->get();
		    	}
		    	if (!empty($result)) {
		    		$tempStock = 0;
		    		foreach ($result as $key => $value) {
		    	 		$tempStock = $tempStock + $value->stock;
		    	 	} 
		    		$stock = $tempStock;
		    	}
		    }	
	    	echo json_encode($stock);exit;
	    }

	    public function getpiths(){
	    	$unitId = $_POST['unitId'];
	    	$response = array();
	    	if (!empty($unitId)) {
	    		$piths = DB::table('masterpithdetails')->select('id', 'Pith')->where('ID_FkUnitID',$unitId)->where('IsActive', 1)->get();
	    	}
	    	echo json_encode($piths);exit;
	    }

	}