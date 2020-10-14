<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminSalesdetailsController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->button_export = false;
			$this->table = "salesdetails";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Customer Name","name"=>"ID_FkCustomerID","join"=>"customerdetails,CustomerName"];
			$this->col[] = ["label"=>"Sales Date","name"=>"SalesDate"];
			$this->col[] = ["label"=>"Total Sales Quantity","name"=>"TotalSalesQty"];
			$this->col[] = ["label"=>"Total Amount","name"=>"TotalAmount"];
			$this->col[] = ["label"=>"Status","name"=>"IsActive","callback_php"=>'($row->IsActive==1)?Active:InActive'];
			$this->col[] = ["label"=>"Added On","name"=>"ActionOn","callback_php"=>'date("d/m/Y h:i:s A",strtotime($row->ActionOn))'];
			$this->col[] = ["label"=>"Added By","name"=>"UserID","join"=>"cms_users,name"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Sales Date','name'=>'SalesDate','type'=>'date','validation'=>'required|date','width'=>'col-sm-10'];
			
			$this->form[] = ['label'=>'Customer Name','name'=>'ID_FkCustomerID','type'=>'select2','width'=>'col-sm-10','datatable'=>'customerdetails,CustomerName', 'validation'=>'required','width'=>'col-sm-10'];

			//$columns[] = ['label'=>'Waste Type','name'=>'ID_PkWasteID','type'=>'select2','width'=>'col-sm-10','datatable'=>'masterwastecategories,CategoryName'];

			$columns[] = ['label'=>'Waste Sub Type','name'=>'ID_PkWasteSCID','type'=>'select','validation'=>'required|integer|min:0','width'=>'col-sm-10','datatable'=>'masterwastesubcategories,SubCategoryName', 'required'=>true];
			$columns[] = ['label'=>'Category','name'=>'ID_FkCategoryID','type'=>'select','width'=>'col-sm-10','datatable'=>'mastercategory,Category'];

			//$columns[] = ['label'=>'Stock Quantity','name'=>'StockQuantity','type'=>'text','width'=>'col-sm-10', 'readonly' => true];

			$columns[] = ['label'=>'Sales Quantity','name'=>'SalesQty','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10', 'required'=>true];
			$columns[] = ['label'=>'Sales Amount','name'=>'Amount','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10', 'required'=>true];

			$this->form[] = ['label'=>'Sales Detail','name'=>'salesdetail','type'=>'child','width'=>'col-sm-10','columns'=>$columns,'table'=>'salessubdetails','foreign_key'=>'ID_FkSalesID'];

			$this->form[] = ['label'=>'Total Sales Quantity','name'=>'TotalSalesQty','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10', 'readonly' => true];
			$this->form[] = ['label'=>'Total Sales Amount','name'=>'TotalAmount','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10', 'readonly'=>true];


			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"SalesDate","name"=>"SalesDate","type"=>"date","required"=>TRUE,"validation"=>"required|date"];
			//$this->form[] = ["label"=>"SalesQty","name"=>"SalesQty","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Amount","name"=>"Amount","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"ID PkWasteSCID","name"=>"ID_PkWasteSCID","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"ID FkCategoryID","name"=>"ID_FkCategoryID","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"IsActive","name"=>"IsActive","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"ActionOn","name"=>"ActionOn","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			//$this->form[] = ["label"=>"UserID","name"=>"UserID","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"IsSync","name"=>"IsSync","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
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
	        $this->load_js = array(asset("js/sales.js"));
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = "tfoot{display:none;} .disabled.day{
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
	    	$postdata['SalesDate'] = date('Y-m-d', strtotime($postdata['SalesDate']));
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
	    	$postdata['SalesDate'] = date('Y-m-d', strtotime($postdata['SalesDate']));
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
	    	DB::table('salesdetails')->where('id',$id)->update(['IsActive' => 0]);
	    	DB::table('salessubdetails')->where('ID_FkSalesID',$id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
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
	    public function getcategory(){
	    	$wasteSubId = $_POST['wasteSubId'];
	    	$response = array();
	    	if (!empty($wasteSubId)) {
	    		$wasteCategory = DB::table('mastercategory')->select('id', 'Category')->where('ID_PkWasteSCID',$wasteSubId)->where('IsActive', 1)->get();
	    	}
	    	echo json_encode($wasteCategory);exit;
	    }

	    public function getsalesstock(){
	    	$stock = 0;$response = array();
	    	$wasteId = $_POST['wasteId'];
	    	$wasteSubId = $_POST['wasteSubId'];
	    	if (!empty($wasteId) && !empty($wasteSubId)) {
		    	if ($wasteId == 1) { //Wet Waste Type
		    		$compost_result = DB::table('compostmain')->select(DB::raw('SUM(TotalQty) as stock'))->join('compostdetail', 'compostmain.id', '=', 'compostdetail.ID_FkCompostID')->join('masterwastesubcategories', 'masterwastesubcategories.id', '=', 'compostdetail.ID_FkWasteSCID')->where('ID_FkWasteSCID',$wasteSubId)->where('compostmain.IsActive', 1)->get();

		    		if (!empty($compost_result)) {
			    		$tempcStock = 0;
			    		foreach ($compost_result as $key => $value) {
			    	 		$tempcStock = $tempcStock + $value->stock;
			    	 	} 
		    		$cstock = $tempcStock;
		    		}

		    		$sale_result = DB::table('salesdetails')->select(DB::raw('SUM(TotalSalesQty) as stock'))->join('salessubdetails', 'salesdetails.id', '=', 'salessubdetails.ID_FkSalesID')->where('salessubdetails.ID_PkWasteSCID',$wasteSubId)->where('salesdetails.IsActive', 1)->get();

		    		if (!empty($sale_result)) {
			    		$tempsStock = 0;
			    		foreach ($sale_result as $key => $value) {
			    	 		$tempsStock = $tempsStock + $value->stock;
			    	 	} 
			    		$sstock = $tempsStock;

			    	}
			    	$stock = $cstock - $sstock;

		    	} else { //other
		    		
		    		$compost_result = DB::table('processingdetails')->select(DB::raw('SUM(ProcessQty) as stock'))->where('ID_PkWasteSCID',$wasteSubId)->where('processingdetails.IsActive', 1)->get();

		    		if (!empty($compost_result)) {
			    		$tempcStock = 0;
			    		foreach ($compost_result as $key => $value) {
			    	 		$tempcStock = $tempcStock + $value->stock;
			    	 	} 
		    		$cstock = $tempcStock;
		    		}

		    		$sale_result = DB::table('salesdetails')->select(DB::raw('SUM(TotalSalesQty) as stock'))->join('salessubdetails', 'salesdetails.id', '=', 'salessubdetails.ID_FkSalesID')->where('salessubdetails.ID_PkWasteSCID',$wasteSubId)->where('salesdetails.IsActive', 1)->get();

		    		if (!empty($sale_result)) {
			    		$tempsStock = 0;
			    		foreach ($sale_result as $key => $value) {
			    	 		$tempsStock = $tempsStock + $value->stock;
			    	 	} 
			    		$sstock = $tempsStock;

			    	}
			    	$stock = $cstock - $sstock;
		    	}

		    }	
	    	echo json_encode($stock);exit;
	    }

	    public function getwastetype(){
	    	$response = array();
	    	$wasteType = DB::table('masterwastecategories')->select('id', 'CategoryName')->where('IsActive', 1)->get();
	    	echo json_encode($wasteType);exit;
	    }

	    public function getDetail($id) {
		  //Create an Auth
		  if(!CRUDBooster::isRead() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {    
		    CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
		  }
		  $data = [];
		  $data['page_title'] = 'Detail Data';
		  $data['row'] = DB::table('salesdetails')
		  					->select('CustomerName','SalesDate','TotalSalesQty','TotalAmount','SalesQty','Amount','Category','SubCategoryName')
		  					->join('customerdetails', 'customerdetails.id', '=', 'salesdetails.ID_FkCustomerID')
		  					->join('salessubdetails', 'salesdetails.id', '=', 'salessubdetails.ID_FkSalesID')
		  					->join('masterwastesubcategories', 'masterwastesubcategories.id', '=', 'salessubdetails.ID_PkWasteSCID','left')
		  					->join('mastercategory', 'mastercategory.id', '=', 'salessubdetails.ID_FkCategoryID','left')
		  					->where('salesdetails.id',$id)
		  					->get();
		  //Please use cbView method instead view method from laravel
		  $this->cbView('sales.custom_detail_view',$data);
		}

	}