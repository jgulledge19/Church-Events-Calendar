<fieldset>
    <legend>[[+admin_heading]]</legend>
    <ul class="plan">
        <li>
            <label for="sel_status" class="[[!+fi.error.status:notempty=`formError`]]">[[+status_label]]</label> 
            <select name="status" id="sel_status" > 
                [[+fi.status_select]]
            </select>
        </li>
        <li class="full">
            <label for="txt_office_notes" class="[[!+fi.error.office_notes:notempty=`formError`]]">[[+officeNotes_label]]</label>
            <textarea name="office_notes" id="txt_office_notes" >[[+office_notes]]</textarea>
        </li>
    </ul>

</fieldset>