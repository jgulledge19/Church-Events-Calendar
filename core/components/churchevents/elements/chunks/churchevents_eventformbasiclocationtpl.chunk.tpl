    <ul class="plan">
        <li class="full">
            <label for="txt_location_name" class="[[!+fi.error.location_name:notempty=`formError`]]">Location Name</label>
            <input name="location_name" type="text" value="" id="txt_location_name" class="full"  />
        </li>

        <li class="medium">
            <label for="txt_address" class="[[!+fi.error.address:notempty=`formError`]]">Address</label> 
            <input name="address" type="text" value="[[!+fi.address]]" id="txt_address"  />
        </li>
        <li class="spaceLeft">
            <label for="txt_country" class="[[!+fi.error.country:notempty=`formError`]]">Country</label> 
            <input name="country" type="text" value="[[!+fi.country]]" id="txt_country"  />
        </li>
        <li class="spaceRight">
            <label for="txt_city" class="[[!+fi.error.city:notempty=`formError`]]">City</label> 
            <input name="city" type="text" value="[[!+fi.city]]" id="txt_city"  />
        </li>
        <li class="small spaceRight">
            <label for="txt_state" class="[[!+fi.error.state:notempty=`formError`]]">State</label> 
            <input name="state" type="text" value="[[!+fi.state]]" id="txt_state"  />
        </li>
        <li class="small">
            <label for="txt_zip" class="[[!+fi.error.zip:notempty=`formError`]]">Zip</label> 
            <input name="zip" type="text" value="[[!+fi.zip]]" id="txt_zip"  />
        </li>
    </ul>
