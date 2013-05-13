/*===========================================================================*/
CTree = {};

/*===========================================================================*/
CTree.Node = function() {
	var self = this;

	self.Name		= "";
	self.Callback	= null;

	self.NodeList	= Array();

	self.TitleElement		= null;
	self.ExpandElement		= null;
	self.ChildrenElement	= null;

	self.OnRender = function(Element) {
		Element.className = "CTree_Node"; 

		self.TitleElement = XElement.AddChild("", Element, self.Name);

		self.TitleElement.className = "CTree_Node_Title";

		if(self.Callback) {
			self.TitleElement.onclick = self.Callback;
		}

		if(self.NodeList.length > 0) {
			self.ExpandElement = XElement.AddChild("", Element, "");

			self.ExpandElement.className = "CTree_Node_Expand";

			self.ChildrenElement = XElement.AddChild("", Element, "");

			self.ChildrenElement.className = "CTree_Node_Children";
			self.ChildrenElement.style.display = "none";

			for(var i = 0;i < self.NodeList.length;i++) {
				var NewNodeElement = XElement.AddChild("", self.ChildrenElement, "");

				self.NodeList[i].OnRender(NewNodeElement);
			}
		}

		if(self.ExpandElement) {
			self.ExpandElement.onclick = self.ToggleChildren;
		}
	}

	self.ToggleChildren = function() {
		if(self.ChildrenElement == null) return;

		if(self.ChildrenElement.style.display == "none") {
			self.ChildrenElement.style.display = "block";

			self.ExpandElement.className = "CTree_Node_Collapse";
		}else{
			self.ChildrenElement.style.display = "none";

			self.ExpandElement.className = "CTree_Node_Expand";
		}
	}
};

/*===========================================================================*/
CTree.Control = function() {
	var self = this;

	self.NodeList = Array();

	self.OnRender = function(Element) {
		if(Element == null) return;

		XElement.RemoveAllChildren(Element);

		for(var i = 0;i < self.NodeList.length;i++) {
			var NewNodeElement = XElement.AddChild("", Element, "");

			self.NodeList[i].OnRender(NewNodeElement);
		}
	}

	self.AddNode = function(Name, Callback) {
		var Node = new CTree.Node();

		Node.Name		= Name;
		Node.Callback	= Callback;

		self.NodeList.push(Node);

		return Node;
	}

	self.AddChildNode = function(ParentNode, Name, Callback) {
		var Node = new CTree.Node();

		Node.Name		= Name;
		Node.Callback	= Callback;

		ParentNode.NodeList.push(Node);

		return Node;
	}
};

/*===========================================================================*/
