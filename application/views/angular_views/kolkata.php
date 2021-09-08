
        <table cellpadding="0" cellspacing="0" class="table table-responsive">
            <tr>
                <th>Name</th>
                <th>Mobile</th>
                <th></th>
            </tr>
            <tbody ng-repeat="m in Customers">
                <tr>
                    <td>{{m.Name}}</td>
                    <td>{{m.Mobile}}</td>
                    <td><button type="button" class="btn btn-link" ng-click="Remove($index)">Remove</button></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td>New Name: <input placeholder="Name" type="text" ng-model="Name" /></td>
                    <td>New Mobile: <input placeholder="Mobile" type="text" ng-model="Mobile" /></td>
                    <td><input type="button" ng-click="Add()" value="Add" /></td>
                </tr>
            </tfoot>
        </table>