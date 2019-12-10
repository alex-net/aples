
$(function(){
	// обработк добавления яблок .. 
	$('.added-form input[type=button]').on('click',function(e){
		var co=parseInt( $(this).parent().find('input[name="count"]').val());
		$(this).parent().find('input[name="count"]').val('');
		if (isNaN(co) || co<=0){
			alert('Должно быть положительное целое число');
			return ;
		}
		$.post('',{action:'add-aples',count:co},function(r){
			if (r.ok)
				$(window).trigger('aple:update-list');
		});
	});
	// событие обновления списка.. 
	$(window).on('aple:update-list',function(){
		$.post('',{action:'list'},function(r){
			if (typeof r.list=='undefined' || !r.ok)
				return ;
			var root=$('table.aples-list tbody');
			root.html('');
			for(i=0;i<r.list.length;i++){
				var row=$('<tr>');
				var td;
				var fields=['id','color','st','toit'];
				for(var f=0;f<fields.length;f++){
					td=$('<td>');
					td.text(r.list[i][fields[f]]);
					row.append(td);	
				}
				td=$('<td>');
				switch(r.list[i].status){
					case 'n':
						var butdown=$('<button>');
						butdown.data('forid',r.list[i].id);
						butdown.text('Уронить');
						butdown.on('click',function(){
							$.post('',{action:'down',id:$(this).data('forid')},function(res){
								if (res.ok)
									$(window).trigger('aple:update-list');
							});

						});
						td.append(butdown);
						break;
					case 'd':
						var inp=$('<input name="tail" size="3" title="кусок яблока" placeholder="%">');
						td.append(inp);
						var ukus=$('<input type="button" value="Откусить">');
						ukus.data({toit:r.list[i].toit-0,id:r.list[i].id});
						ukus.on('click',function(){
							var percent=$(this).prev('input').val();
							if (percent<=0 || percent>100-$(this).data('toit') || isNaN(percent-0)){
								alert('Нужно число от 1% до '+(100-$(this).data('toit'))+'%' );
								return;
							}
							$.post('',{action:'it',id:$(this).data('id'),size:percent},function(res){
								if (res.ok)
									$(window).trigger('aple:update-list');

							});


						});
						td.append(ukus);



				}
				row.append(td);	


				root.append(row);
			}

		});
	});
	
	$(window).trigger('aple:update-list');

	
});