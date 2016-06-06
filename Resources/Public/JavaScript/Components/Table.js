/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

Ext.ns('HDNET.Importr');

HDNET.Importr.Table = Ext.extend(Ext.grid.GridPanel, {
	constructor: function (config) {
		var store = new Ext.data.ArrayStore({
			fields: this.createFieldsFromData(config.storeData)
		});

		store.loadData(config.storeData);

		config = Ext.apply({
			store: store,
			columns: this.createColumnsFromData(config.storeData),
			stripeRows: true,
			viewConfig: {
				//forceFit: true
			}
		}, config);

		HDNET.Importr.Table.superclass.constructor.call(this, config);
	},
	/**
	 * Creates needed Fields-Array for
	 * ArrayStore fields
	 * @return array
	 */
	createFieldsFromData: function (data) {
		var fields = [], i,
			maxFields = this.countMaxFieldLength(data);
		for (i = 0; i < maxFields; i++) {
			fields.push({name: 'field' + i});
		}
		return fields;
	},

	createColumnsFromData: function (data) {
		var columns = [], i;
		for (i in data[0]) {
			if (typeof data[0][i] != 'function') {
				var label = parseInt(i) + 1;
				columns.push({
					header: 'Column #' + label,
					sortable: true,
					dataIndex: 'field' + i
				});
			}
		}

		return columns;
	},

	/**
	 * Counts the length of the data Array
	 * @return integer
	 */
	countMaxFieldLength: function (data) {
		var maxColumns = 0, i;
		for (i in data) {
			if (typeof data[i] != 'array' && data[i].length > maxColumns) {
				console.log(data[i]);
				maxColumns = data[i].length;
			}
		}
		return maxColumns;
	}
});