
var designType = 'c';
var responsiveType = 's';
var numPages = 1;
var siteType = designType + responsiveType;

function setNumPages (inputNum) {
	if (inputNum == parseInt(inputNum, 10)) {
		numPages = inputNum; 
	} else {
		numPages = 1;
	}
}

function updateNumPages (inputNum) {
	setNumPages (inputNum);
	updatePrice ();
}

function setDesignType (inputType) {
	if ((inputType == 'c') || (inputType == 't')) {
		designType = inputType;
	} else {
		designType = 'c';	
	}
	updateSiteType ();
}

function updateDesignType (inputType) {
	setDesignType (inputType);
	updatePrice ();
}

function setResponsiveType (inputType) {
	if ((inputType == 'r') || (inputType == 's')) {
		responsiveType = inputType;
	} else {
		responsiveType = 's';	
	}
	updateSiteType ();
}

function updateResponsiveType (inputType) {
	setResponsiveType (inputType);
	updatePrice ();
}

function updateSiteType () {
	
	var inputType = designType + responsiveType;
	if ((inputType == 'ts') || (inputType == 'tr') || (inputType == 'cs') || (inputType == 'cr')) {
		siteType = inputType;
	} else {
		siteType = 'ts'
	}
}

function getPagesPrice() {

	var pricePerPage = {
		1 : { 'ts' : 60, 'cs' : 100, 'tr' : 80, 'cr' : 140 }
		2 : { 'ts' : 55, 'cs' : 95, 'tr' : 75, 'cr' : 120 },
		3 : { 'ts' : 50, 'cs' : 90, 'tr' : 65, 'cr' : 110 },
		4 : { 'ts' : 45, 'cs' : 85, 'tr' : 60, 'cr' : 100 },
		5 : { 'ts' : 40, 'cs' : 80, 'tr' : 50, 'cr' : 90 }
	};

	if (numPages == 1) {
		pagesPrice = numPages * pricePerPage[1][siteType];
	} else if (numPages == 2) {
		pagesPrice = numPages * pricePerPage[2][siteType];
	} else if ((numPages >= 3) && (numPages <= 5)) {
		pagesPrice = numPages * pricePerPage[3][siteType];
	} else if ((numPages >= 6) && (numPages <= 9)) {
		pagesPrice = numPages * pricePerPage[4][siteType];
	} else if (numPages >= 10) {
		pagesPrice = numPages * pricePerPage[5][siteType];
	}

	return pagesPrice;
}

function getTotalPrice () {
	price = getPagesPrice ();
	return price;
}

function updatePrice () {
	$('span#total').html(getTotalPrice());
} 


/*function testData (sampleSet) {
	setNumPages (sampleSet['0']);
	setDesignType (sampleSet[1]);
	setResponsiveType (sampleSet[2]);
	if (getTotalPrice () != sampleSet[3]) {
		console.log("testData[" + i + "] failed");
	}
}*/

function testing () {

	var sampleData = [
	[1,'t','s',60],
	[1,'c','s',100],
	[1,'t','r',80],
	[1,'c','r',140],
	 
	[2,'t','s',110],
	[2,'c','s',190],
	[2,'t','r',150],
	[2,'c','r',240],
	
	[3,'t','s',150],
	[3,'c','s',270],
	[3,'t','r',195],
	[3,'c','r',330],
	 
	[4,'t','s',200],
	[4,'c','s',360],
	[4,'t','r',260],
	[4,'c','r',440],
	 
	[5,'t','s',250],
	[5,'c','s',450],
	[5,'t','r',325],
	[5,'c','r',550],
	 
	[6,'t','s',270],
	[6,'c','s',510],
	[6,'t','r',360],
	[6,'c','r',600],
	 
	[7,'t','s',315],
	[7,'c','s',595],
	[7,'t','r',420],
	[7,'c','r',700],
	 
	[9,'t','s',405],
	[9,'c','s',765],
	[9,'t','r',540],
	[9,'c','r',900],
	 
	[10,'t','s',400],
	[10,'c','s',800],
	[10,'t','r',500],
	[10,'c','r',900],
	 
	[11,'t','s',440],
	[11,'c','s',880],
	[11,'t','r',550],
	[11,'c','r',990],
	 
	[15,'t','s',600],
	[15,'c','s',1200],
	[15,'t','r',750],
	[15,'c','r',1350],
	
	];
	var failed = 0, passed = 0;
	for (var i = 0, len = sampleData.length; i < len; i++) {
		//testData(sampleData[i]);
		
		setNumPages (sampleData[i][0]);
		setDesignType (sampleData[i][1]);
		setResponsiveType (sampleData[i][2]);
		if (getTotalPrice () != sampleData[i][3]) {
			console.log("sampleData[" + i + "] failed");
			failed = failed + 1;
		} else {
			passed = passed + 1;
		}
	}
	
	console.log("total = " + sampleData.length); 
	console.log("failed = " + failed);
	console.log("passed = " + passed);

}

$(document).ready (function () {
	setNumPages ($('[name="numPages"]').val());
	setDesignType ($('[name="design"]').val());
	setResponsiveType ($('[name="responsive"]').val());
	updatePrice ();
	
	testing ();
	
});


