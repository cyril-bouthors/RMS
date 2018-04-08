	</div>
	<div data-role="content" data-theme="a">
		
		<? $title = "Stats for the last 2 days"; if(isset($form_values['date'])) $title = "Stats for: ".$form_values['date']; ?>
		<h4><?=$title?></h4>
		<table data-role="table" id="table-column-toggle" data-mode="columntoggle" class="ui-responsive table-stroke">
		  <thead>
		    <tr>
			  <th data-priority="1">USER</th>
		      <th data-priority="2">Total Burger</th>
		      <th data-priority="2">Total Dessert</th>
		      <th data-priority="1">Ratio dessert</th>
		      <th data-priority="2">Total Potatoes</th>
	          <th data-priority="2">Total Cheese</th>
			  <th data-priority="1">Ratio cheese</th>
		      <th data-priority="4">Total CA</th>
		    </tr>
		  </thead>
		<? foreach($stats_sorted as $key => $val) { 
			if(!empty($val['total'])) { ?>
		  <tbody>
		    <tr>
		      <td><?=$val['name']?></td>
		      <td><?=$val['burger']?></td>
		      <td><?=$val['dessert']?></td>
		      <td><? if(!empty($val['dessert'])) echo round(($val['dessert']*100)/$val['total'], 2); ?>%</td>
		      <td><?=$val['potatoes']?></td>
			  <td><?=$val['cheese']?></td>
			  <td><? if(!empty($val['potatoes'])) echo round(($val['cheese']*100)/$val['potatoes']); ?>%</td>
			  <td><?=$val['total']?>€</td>
		    </tr>
		  </tbody>
<? } } ?>
<tbody>
  <tr>
    <td>TOTAL</td>
    <td><?=$sum_burger?></td>
    <td><?=$sum_dessert?></td>
    <td>-</td>
    <td><?=$sum_potatoes?></td>
	  <td><?=$sum_cheese?></td>
	  <td>-</td>
	  <td>-</td>
  </tr>
</tbody>
	</table>
	<br />
	
	<form id="pos" name="pos" method="post" action="/webcashier/stats">
	<label for="date" id="date_label"><i>Pick a date, format: YYYY-MM-DD example : 2019-01-28:</i></label>
	<input type="text" name="date" id="date" data-clear-btn="true" value="<? if(isset($form_values['date'])) echo $form_values['date']; ?>"/>
	<input type="submit" name="SELECT" value="SELECT">
	</div>
</div>