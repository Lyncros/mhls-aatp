//=============================================================================
CPaymentGateways = {};

//=============================================================================
CPaymentGateways.Class = function() {
	var self			= this;

	var PaymentGatewaysName	= "";
	var PaymentGatewaysID	= 0;

	self.TransID		= 0;

	self.Invoice		= "";
	self.PONumber		= "";
	self.Description	= "";

	self.Billing		= {};
	self.Shipping		= {};

	self.CCNumber		= "";
	self.CCExp			= "";
	self.CCSecurity		= "";

	//-------------------------------------------------------------------------
	var Go = function(Action, Parms, Callback) {
		Parms["PaymentGatewaysID"]	= PaymentGatewaysID;
		Parms["TransID"]			= self.TransID;

		Parms["Invoice"]			= self.Invoice;
		Parms["PONumber"]			= self.PONumber;
		Parms["Description"]		= self.Description;

		Parms["BillingCompany"]		= self.Billing["Company"];
		Parms["BillingFirstName"]	= self.Billing["FirstName"];
		Parms["BillingLastName"]	= self.Billing["LastName"];
		Parms["BillingAddress1"]	= self.Billing["Address1"];
		Parms["BillingAddress2"]	= self.Billing["Address2"];
		Parms["BillingCity"]		= self.Billing["City"];
		Parms["BillingState"]		= self.Billing["State"];
		Parms["BillingZip"]			= self.Billing["Zip"];
		Parms["BillingCountry"]		= self.Billing["Country"];
		Parms["BillingPhone"]		= self.Billing["Phone"];
		Parms["BillingFax"]			= self.Billing["Fax"];

		Parms["ShippingCompany"]	= self.Shipping["Company"];
		Parms["ShippingFirstName"]	= self.Shipping["FirstName"];
		Parms["ShippingLastName"]	= self.Shipping["LastName"];
		Parms["ShippingAddress1"]	= self.Shipping["Address1"];
		Parms["ShippingAddress2"]	= self.Shipping["Address2"];
		Parms["ShippingCity"]		= self.Shipping["City"];
		Parms["ShippingState"]		= self.Shipping["State"];
		Parms["ShippingZip"]		= self.Shipping["Zip"];
		Parms["ShippingCountry"]	= self.Shipping["Country"];

		Parms["CCNumber"]			= self.CCNumber;
		Parms["CCExp"]				= self.CCExp;
		Parms["CCSecurity"]			= self.CCSecurity;

		CAJAX.Add(PaymentGatewaysName, "PaymentGateway", Action, Parms, Callback);
	}

	//-------------------------------------------------------------------------
	self.SetPaymentGatewaysName = function(Name) {
		PaymentGatewaysName = Name;
	}

	//-------------------------------------------------------------------------
	self.SetPaymentGatewaysID = function(ID) {
		PaymentGatewaysID = ID;
	}

	//-------------------------------------------------------------------------
	self.Authorize = function(Amount, Callback) {
		Go("Authorize", {'Amount' : Amount}, Callback);
	}

	//-------------------------------------------------------------------------
	self.Charge = function(Amount, Callback) {
		Go("Charge", {'Amount' : Amount}, Callback);
	}

	//-------------------------------------------------------------------------
	self.Void = function(Callback) {
		Go("Void", {}, Callback);
	}

	//-------------------------------------------------------------------------
	self.Shipped = function(ShippingMethod, TrackingNumber, Callback) {
		Go("Shipped", {'ShippingMethod' : ShippingMethod, 'TrackingNumber' : TrackingNumber}, Callback);
	}

	//-------------------------------------------------------------------------
	self.eCheck = function(Amount, Callback) {
		Go("eCheck", {'Amount' : Amount}, Callback);
	}

	//-------------------------------------------------------------------------
	self.Credit = function(Amount, Callback) {
		Go("Credit", {'Amount' : Amount}, Callback);
	}
};

//=============================================================================
