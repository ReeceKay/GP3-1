<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	 function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('grocery_CRUD');
		$this->load->library('table');
	}

	public function index()
	{
		$this->load->view('header');
		$this->load->view('home');
	}

	public function bookings()
	{
		$this->load->view('header');
		$crud = new grocery_CRUD();
		$crud->set_theme('datatables');

		//table name exact from database
		$crud->set_table('booking');

		//give focus on name used for operations e.g. Add Order, Delete Order
		$crud->set_subject('booking');

		//the columns function lists attributes you see on frontend view of the table
		$crud->columns('bookingNo', 'performanceID', 'seatsQuantity', 'seatCost', 'filmTitle', 'memberNo');

		//the fields function lists attributes to see on add/edit forms.
		//Note no inclusion of invoiceNo as this is auto-incrementing
		$crud->fields('bookingNo', 'performanceID', 'seatCost', 'filmTitle', 'memberNo');

		//set the foreign keys to appear as drop-down menus
		// ('this fk column','referencing table', 'column in referencing table')
		$crud->set_relation('memberNo','members','memberNo');

		//many-to-many relationship with link table see grocery crud website: www.grocerycrud.com/examples/set_a_relation_n_n
		//('give a new name to related column for list in fields here', 'join table', 'other parent table', 'this fk in join table', 'other fk in join table', 'other parent table's viewable column to see in field')
		//$crud->set_relation_n_n('items', 'order_items', 'items', 'invoice_no', 'item_id', 'itemDesc');

		//form validation (could match database columns set to "not null")
		//$crud->required_fields('invoiceNo', 'date', 'custID');

		//change column heading name for readability ('columm name', 'name to display in frontend column header')
		$crud->display_as('bookingNo', 'Booking Number');
		$crud->display_as('performanceID', 'Performance ID');
		$crud->display_as('seatsQuantity', 'Quantity of seats');
		$crud->display_as('seatCost', 'Price Per Seat');
		$crud->display_as('filmTitle', 'Film');
		$crud->display_as('memberNo', 'Member');




		$output = $crud->render();
		$this->bookings_output($output);
	}

	function bookings_output($output = null)
	{
		//this function links up to corresponding page in the views folder to display content for this table
		$this->load->view('bookings_view.php', $output);
	}

	//========================================================================================================

	public function films()
	{
		$this->load->view('header');
		$crud = new grocery_CRUD();
		$crud->set_theme('datatables');

		$crud->set_table('film');
		$crud->set_subject('film');
		$crud->columns('filmTitle', 'director', 'releaseYear');
		$crud->fields('filmTitle', 'director', 'releaseYear');
		$crud->required_fields('filmNo', 'filmTitle');
		//$crud->set_relation_n_n('orders', 'order_items', 'orders', 'item_id', 'invoice_no', 'invoiceNo');
		$crud->display_as('filmTitle', 'Film');

		$output = $crud->render();
		$this->films_output($output);
	}

	function films_output($output = null)
	{
		$this->load->view('films_view.php', $output);
	}
	//========================================================================================================

	public function cinemas()
	{
		$this->load->view('header');
		$crud = new grocery_CRUD();
		$crud->set_theme('datatables');

		$crud->set_table('cinema');
		$crud->set_subject('cinema');
		$crud->columns('name', 'location', 'address');
		$crud->fields('name', 'location', 'address');
		$crud->required_fields('name', 'location', 'address');
		//$crud->set_relation_n_n('orders', 'order_items', 'orders', 'item_id', 'invoice_no', 'invoiceNo');
		//$crud->display_as('itemDesc', 'Description');

		$output = $crud->render();
		$this->cinemas_output($output);
	}

	function cinemas_output($output = null)
	{
		$this->load->view('cinemas_view.php', $output);
	}

//========================================================================================================

	public function performances()
	{
		$this->load->view('header');
		$crud = new grocery_CRUD();
		$crud->set_theme('datatables');
		$crud->set_table('performance');
		$crud->set_subject('Performance');
		//$crud->columns('name', 'screenNo', 'filmNo', 'date', 'startTime');
		//$crud->fields('name', 'screenNo', 'filmTitle', 'date', 'startTime');
		//$crud->required_fields('custID', 'custName', 'custAddress', 'custTown', 'custPostcode', 'custTel', 'custEmail');
		$crud->set_relation('filmNo', 'film', 'filmTitle');
		$crud->set_relation('cinemaNo', 'cinema', 'name');
		$crud->display_as('filmnNo', 'filmTitle');
		$crud->display_as('cinemaNo', 'name');

		$output = $crud->render();
		$this->performances_output($output);
	}

	function performances_output($output = null)
	{
		$this->load->view('performances_view.php', $output);
	}

	//========================================================================================================

	public function orderline()
	{
		$this->load->view('header');
		$crud = new grocery_CRUD();
		$crud->set_theme('datatables');
		$crud->set_table('order_items');
		$crud->set_subject('order line');
		$crud->fields('invoice_no', 'item_id', 'itemQty', 'itemPrice');
		$crud->set_relation('invoice_no','orders','invoiceNo');
		//have multiple columns show in one FK column by concatenation:  www.grocerycrud.com/forums/topic/479-concatenate-two-or-more-fields-into-one-field/
		$crud->set_relation('item_id','items','{itemID} - {itemDesc}');
		$crud->required_fields('invoice_no', 'item_id', 'itemQty', 'itemPrice');
		$crud->display_as('invoice_no', 'InvoiceNo');
		$crud->display_as('item_id', 'ItemID');
		$crud->display_as('itemQty', 'Quantity');
		$crud->display_as('itemPrice', 'Price');

		$output = $crud->render();
		$this->orderline_output($output);
	}

	function orderline_output($output = null)
	{
		$this->load->view('orderline_view.php', $output);
	}

	public function querynav()
	{
		$this->load->view('header');
		$this->load->view('querynav_view');
	}

	public function query1()
	{
		$this->load->view('header');
		$this->load->view('query1_view');
	}

	public function query2()
	{
		$this->load->view('header');
		$this->load->view('query2_view');
	}

	public function blank()
	{
		$this->load->view('header');
		$this->load->view('blank_view');
	}
}
