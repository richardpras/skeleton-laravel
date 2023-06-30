<?php

namespace App\Http\Classes;
use App\Http\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Utility
{

    public static function getLastID($tabelName)
    {
        $statement = DB::select("SHOW TABLE STATUS LIKE '{$tabelName}'");
        $nextId = $statement[0]->Auto_increment;
        return $nextId;
    }

    public static function getUser($id)
    {
        $model = User::find($id);
        return $model;
    }

    public static function getUsername($id)
    {
        $model = User::find($id);
        $username = "-";
        if ($model != null) {
            $username = $model->username;
        }

        return $username;
    }

    public static function getFormatDate($value, $format = 'd F Y H:i:s')
    {
        $date = '';
        if ($value != null) {
            $date = date($format, strtotime($value));
        }

        return $date;
    }

    public static function getFormatCurrency($value, $prefix = '', $usePrefix = true)
    {
        if ($prefix == '') {
            $prefix = 'Rp ';
        }
        $price = 0;
        if ($value != null) {
            if ($usePrefix) {
                $price = $prefix . number_format($value, 2, '.', ',');
            } else {
                $price = number_format($value, 2, '.', ',');
            }
        }

        return $price;
    }

    public static function showStatus($value)
    {
        $text = "";
        switch ($value) {
            case 1:
                $text = 'Active';
                break;
            case 0:
                $text = 'Non Active';
                break;
        }
        return $text;
    }

    public static function showStatusPay($value)
    {
        $text = "";
        switch ($value) {
            case 1:
                $text = 'Active';
                break;
            case 0:
                $text = 'Void';
                break;
            case 2:
                $text = 'Done';
                break;
        }
        return $text;
    }

    public static function showStatusSales($value)
    {
        $text = "";
        switch ($value) {
            case 1:
                $text = 'Active';
                break;
            case 0:
                $text = 'Cancel';
                break;
        }
        return $text;
    }


    public static function showStatusJournal($value)
    {
        $text = "";
        switch ($value) {
            case 1:
                $text = 'Active';
                break;
            case 2:
                $text = 'Closing';
                break;
            case 0:
                $text = 'Void';
                break;
        }
        return $text;
    }


    public static function showStatusWH($value)
    {
        $text = "";
        switch ($value) {
            case 0:
                $text = 'Waiting HO Approval';
                break;
            case 1:
                $text = 'Waiting For GR';
                break;
            case 2:
                $text = 'Done';
                break;
            case 3:
                $text = 'Canceled';
                break;
        }
        return $text;
    }

    public static function showStatusConfig($value)
    {
        $text = "";
        switch ($value) {
            case 'COA1':
                $text = 'Clarification';
                break;
            case 'COA2':
                $text = 'Financial Statements';
                break;
            case 'COA3':
                $text = 'Ledger';
                break;
        }
        return $text;
    }

    

    //Menampilkan status dalam Pembelian
    public static function showHierarchy($value)
    {
        $text = "";
        switch ($value) {
            case 1:
                $text = 'Waiting For Approval';
                break;
            case 2:
                $text = 'Procurement';
                break;
            case 3:
                $text = 'Rejected';
                break;
            case 4:
                $text = 'Waiting HO Approval';
                break;
            case 5:
                $text = 'Waiting For GR';
                break;
            case 6:
                $text = 'PO Rejected';
                break;
            case 7:
                $text = 'Waiting For Payment';
                break;
            case 8:
                $text = 'Done';
                break;
            case 9:
                $text = 'Warehouse Transfer';
                break;
        }
        return $text;
    }

    //Menampilkan tipe-tipe dalam Pembelian
    public static function showTypePembelian($value)
    {
        $text = "";
        switch ($value) {
            case 1:
                $text = 'Pembelian';
                break;
            case 2:
                $text = 'Penerimaan';
                break;
        }
        return $text;
    }

    //Menampilkan tipe-tipe dalam Transaksi
    public static function showTypeTransaction($value)
    {
        $text = "";
        switch ($value) {
            case 1:
                $text = 'Pembelian';
                break;
            case 2:
                $text = 'Penjualan';
                break;
        }
        return $text;
    }

    //Menampilkan Persetujuan
    public static function showType($value)
    {
        $text = "";
        switch ($value) {
            case 1:
                $text = 'Approve';
                break;
            case 2:
                $text = 'Not Approve';
                break;
        }
        return $text;
    }

    //Menampilkan Metode Pengembalian dalam Pembelian
    public static function showRetrunMethod($value)
    {
        $text = "";
        switch ($value) {
            case 1:
                $text = 'CN';
                break;
            case 2:
                $text = 'Cash';
                break;
        }
        return $text;
    }

    //Menampilkan Darimana Transaksi berasal pada InventoryCard
    public static function showTranSource($value)
    {
        $text = "";
        switch ($value) {
            case 1:
                $text = 'Purchasing';
                break;
            case 2:
                $text = 'PurchaseOrder';
                break;
            case 3:
                $text = 'Receiving';
                break;
        }
        return $text;
    }

   
}
