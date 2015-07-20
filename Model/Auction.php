<?php

class AuctionItem {

	#int id of auction
	public $id;
	#name of auction item
	public $name;
	#description of item
	public $description;
	#creation date of auction
	public $createdDate
	#start date of auction
	public $startDate;
	#end date of auction
	public $endDate;
	#double staring dollar amount of auction
	public $startBid;
	#double reserve price of auction
	public $reservePrice;
	#current bid price
	public $currentBidPrice;
	#current winning bid id
	public $winningBidId;
	#category of item
	public $categoryId;

	#array of all the bids
	public $bids;


	//functions
	
	function getBids() {



	}

}



?>
