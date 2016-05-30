<?php
/**
 * Created by Harry Kurniawan.
 * Date: 5/30/16
 * Time: 1:45 PM
 */

/*
	pendefinisian namespace
*/
namespace models;


/*
	setup untuk php error message, dapat juga dilakukan secara global pada file php.ini
*/
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);

/*
	memanggil file yg berisi code untuk koneksi
*/
include_once('connection.php');

/*
	inisialiasi namespace
*/
use utilities\connection as connect;

/*
	class model, yang akan diisi beberapa fungsi untuk mengakomodir request
	- menggunakan metode inheritance terhadap abstract class connection yang ada pada file connection.php
*/
class model extends connect
{

	/*
		fungsi yang menampilkan semua data rute yg ada
		menggunakan mysqli prepare statement
		- return = array.
	*/
	public function getAllRoute()
	{
		$_query = "select * from route order by id asc";
		$_stmt = $this->getConnection()->prepare($_query);
		$_return = array();
		if ($_stmt) {
			$_stmt->execute();
			$_stmt->bind_result($id, $lat, $lng, $terminal);
			$_i = 0;
			while ($_stmt->fetch()) {
				$_return[$_i]['lat'] = $lat;
				$_return[$_i]['lng'] = $lng;
				$_return[$_i]['terminal'] = $terminal;
				$_i++;
			}
			$_stmt->close();
		} else {
			printf("Error message: %s\n", $this->getConnection()->error);
		}
		return $_return;
	}

	/*
		fungsi yang menampilkan kordinat dari kendaraan
		menggunakan mysqli prepare statement
		- return = array.
	*/
	public function getCar()
	{
		$_query = "select code, lat, lng from kendaraan order by id asc";
		$_stmt = $this->getConnection()->prepare($_query);
		$_return = array();
		if ($_stmt) {
			$_stmt->execute();
			$_stmt->bind_result($code, $lat, $lng);
			$_i = 0;
			while ($_stmt->fetch()) {
				$_return[$_i]['code'] = $code;
				$_return[$_i]['lat'] = $lat;
				$_return[$_i]['lng'] = $lng;
				$_i++;
			}
			$_stmt->close();
		} else {
			printf("Error message: %s\n", $this->getConnection()->error);
		}
		return $_return;
	}

}

/*
	mendefinisikan request yang diterima
*/
$REQUEST = $_SERVER['REQUEST_METHOD'];
/*
	variabel untuk menampung data method
*/
$DATA = array();
/*
	variabel untuk menginisialisasi class model yang akan digunakan
*/
$CLASS_INIT = new model();

/*
	pengecekan jenis request
*/
switch ($REQUEST) {
	case "GET":
		$DATA = $_GET;
		/*
			Representasi data berupa JSON format.
		*/
		echo json_encode($CLASS_INIT->$DATA['action']());
		break;
}