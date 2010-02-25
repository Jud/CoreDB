#CoreDB &mdash; Beautify Mysqli With Objects

CoreDB is just a wrapper class for Mysql. Why, then, did I create it? Because all the other classes are over-complicated or just plain awful. CoreDB is used in all of my projects and allow me to focus on writing code, not making sure my insert statements have all of their backticks. 

##Usage

	// init class
	$db = new CoreDB();
	
	// Create sql
	$fields = array('field1', 'field2', 'field3');
	$tables = array('table1 t', 'table2 tt');
	$where  = 't.field1=tt.field1 and t.field1 < 5';
	$db->query($fields, $tables, $where);
	
	while($data = $db->fetch($query))
	{
		// code
	}
	
