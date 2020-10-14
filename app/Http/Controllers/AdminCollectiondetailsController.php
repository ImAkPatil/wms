<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminCollectiondetailsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = false;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "collectionmain";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Date","name"=>"CollectionDate"];
			$this->col[] = ["label"=>"Vehical Number","name"=>"ID_FkDriverID","join"=>"masterdriverdetails,VehicalNumber"];
			$this->col[] = ["label"=>"Trip Number","name"=>"ID_FkTripID","join"=>"mastertrips,TripNumber"];
			$this->col[] = ["label"=>"Total Waste Qty","name"=>"TotalWasteQty"];
			$this->col[] = ["label"=>"Description","name"=>"Description"];
			$this->col[] = ["label"=>"Status","name"=>"IsActive","callback_php"=>'($row->IsActive==1)?Active:InActive'];
			$this->col[] = ["label"=>"Action On","name"=>"ActionOn","callback_php"=>'date("d/m/Y h:i:s A",strtotime($row->ActionOn))'];
			$this->col[] = ["label"=>"Added By","name"=>"UserID","join"=>"cms_users,name"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Collection Date','name'=>'CollectionDate','type'=>'date','validation'=>'required|date','width'=>'col-sm-10', 'data-date-end-date'=>"1d"];
			$this->form[] = ['label'=>'Vehical Number','name'=>'ID_FkDriverID','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'masterdriverdetails,VehicalNumber','datatable_where'=>'IsActive = 1'];
			$this->form[] = ['label'=>'Trip Number','name'=>'ID_FkTripID','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'mastertrips,TripNumber','datatable_where'=>'IsActive = 1'];
			
			$columns[] = ['label'=>'Waste Category','name'=>'ID_FkWasteID','width'=>'col-sm-10','type'=>'select','datatable'=>'masterwastecategories,CategoryName','required'=>true,'datatable_where'=>'IsActive = 1'];
			$columns[] = ['label'=>'QTY','name'=>'WasteQty','width'=>'col-sm-10', 'type'=>'text','required' => true];
			$columns[] = ['label'=>'UOM','name'=>'UOM','type'=>'text','width'=>'col-sm-10','required'=>true, 'readonly' => true];
			
			$this->form[] = ['label'=>'Collection Detail','name'=>'collectiondetail','type'=>'child','width'=>'col-sm-10','columns'=>$columns,'table'=>'collectiondetail','foreign_key'=>'ID_FkCollectionID'];
			
			$this->form[] = ['label'=>'Total Waste Qty','name'=>'TotalWasteQty','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10', 'readonly'=> true];
			$this->form[] = ['label'=>'Description','name'=>'Description','type'=>'textarea','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Collection Date','name'=>'CollectionDate','type'=>'date','validation'=>'required|date','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Vehical Number','name'=>'ID_FkDriverID','type'=>'select2','validation'=>'required','width'=>'col-sm-10','datatable'=>'masterdriverdetails,VehicalNumber'];
			//$this->form[] = ['label'=>'Trip Number','name'=>'ID_FkTripID','type'=>'select2','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'mastertrips,TripNumber'];
			//$this->form[] = ['label'=>'Total Waste Qty','name'=>'TotalWasteQty','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Description','name'=>'Description','type'=>'textarea','width'=>'col-sm-10'];
			//
			//$columns[] = ['label'=>'Waste Category','name'=>'ID_FkWasteID', 'width'=>'col-sm-10','type'=>'select','datatable'=>'masterwastecategories,CategoryName','required'=>true];
			//$columns[] = ['label'=>'QTY','name'=>'WasteQty','width'=>'col-sm-10', 'type'=>'text','required'=>true];
			//$columns[] = ['label'=>'UOM','name'=>'UOM','type'=>'text','required'=>true];
			//// $columns[] = ['label'=>'Sub Total','name'=>'subtotal','type'=>'number','formula'=>"[qty] * [price] - [discount]","readonly"=>true,'required'=>true];
			//$this->form[] = ['label'=>'Collection Detail','name'=>'collectiondetail','type'=>'child','columns'=>$columns,'table'=>'collectiondetail','foreign_key'=>'ID_FkCollectionID'];
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
	        $this->script_js = null;
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
	        $this->load_js = array(asset("js/collection.js"));
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = "tfoot{display:none;}
	        #table-collectiondetail tbody tr td span.td-label{
	        font-weight:normal;
	    }
	       .disabled.day{
	       	color:#9b9898 !important;
	       } ";
	        
	        
	        
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
	        $postdata['CollectionDate'] = date('Y-m-d', strtotime($postdata['CollectionDate']));
	    	$postdata['IsActive'] = 1;
	        $postdata['ActionOn'] = date('Y-m-d H:i:s');
	        $postdata['UserID'] = CRUDBooster::myId();
	        if($postdata['TotalWasteQty'] <= 0){
	        	CRUDBooster::redirect(CRUDBooster::mainpath("add/"),"Total Waste Quantity should not be zero or less than zero","warning");
	        }
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
	        // echo '<pre>';
	        // print_r($postdata);
	        // exit;
	        $postdata['CollectionDate'] = date('Y-m-d', strtotime($postdata['CollectionDate']));
	    	$postdata['IsActive'] = 1;
	        $postdata['ActionOn'] = date('Y-m-d H:i:s');
	        $postdata['UserID'] = CRUDBooster::myId();
	        if($postdata['TotalWasteQty'] <= 0){
	        	CRUDBooster::redirect(CRUDBooster::mainpath("edit/".$id),"Total Waste Quantity should not be zero or less than zero","warning");
	        }

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
	    	DB::table('collectionmain')->where('id',$id)->update(['IsActive' => 0]);
	    	DB::table('collectiondetail')->where('ID_FkCollectionID',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
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

	    public function gettrips(){
	    	$VehicalNumber = $_POST['vehicalnumber'];
	    	$CollectionDate = date('Y-m-d',strtotime($_POST['CollectionDate']));
	    	$response = array();
	    	if (!empty($VehicalNumber)) {
	    		$TripNumber = DB::table('collectionmain')->select('ID_FkTripID')->where('ID_FkDriverID',$VehicalNumber)->where('CollectionDate',$CollectionDate)->where('IsActive',1)->get();
	    	}
	    	echo json_encode($TripNumber);exit;
	    }
	}