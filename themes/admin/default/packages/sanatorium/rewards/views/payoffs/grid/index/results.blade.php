<script type="text/template" data-grid="payoff" data-template="results">

	<% _.each(results, function(r) { %>

		<tr data-grid-row>
			<td><input content="id" input data-grid-checkbox="" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="<%= r.edit_uri %>" href="<%= r.edit_uri %>"><%= r.id %></a></td>
			<td><%= r.points %></td>
			<td><%= r.payoff_money %></td>
			<td><%= r.payoff_id %></td>
			<td><%= r.payoff_type %></td>
			<td><%= r.created_at %></td>
		</tr>

	<% }); %>

</script>
