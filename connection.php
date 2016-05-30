<?php
/**
 * Created by Harry Kurniawan.
 * Date: 5/30/16
 * Time: 1:54 PM
 */

/*
	pendefinisian namespace
*/
namespace utilities;

/*
	abstract class yang akan digunakan sebagai penyedia koneksivitas data
*/
abstract class connection
{

	var $CONNECTION;

	/*
		deklarasi koneksi
	*/
	function __construct()
	{
		$_servername = "localhost";
		$_username = "root";
		$_password = "root";
		$_database = "maps_testing";

		$_conn = new \mysqli($_servername, $_username, $_password);

		if ($_conn->connect_error) {
			die("Connection failed: " . $_conn->connect_error);
		} else {
			mysqli_select_db($_conn, $_database);
			$this->CONNECTION = $_conn;
		}
	}

	/*
		function untuk memanggil object koneksi
	*/
	protected function getConnection()
	{
		return $this->CONNECTION;
	}

}