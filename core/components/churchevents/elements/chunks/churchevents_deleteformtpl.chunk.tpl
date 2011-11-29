[[!+fi.validation_error_message]]
[[+fi.errorMessage]]
[[+returnMessage]]
<form name="event_form" action="" method="post"  class="standard" >

    <fieldset>
        <legend>[[+delete_heading]]</legend>
        <input name="a" type="hidden" value="0"/>
        <input name="view" type="hidden" value="[[+fi.view]]"  />
        <input name="event_id" type="hidden" value="[[+fi.event_id]]"  />
        
        [[+repeatOptions]]
        
        <ul class="plan">
            <li class="spaceRight"><input name="submit" type="submit" value="[[+delete_button]]" class="submit" /></li>
            <li><input name="cancel" type="submit" value="[[+cancel_button]]" class="submit" /></li>
        </ul>
    
    </fieldset>
    <hr />
    <h2>[[+title_label]]</h2>
    <p>[[!+fi.title]]</p>
    
    <h3>[[+publicDesc_label]]</h3>
    <p>[[!+fi.public_desc]]</p>
    
    <h3>[[+notes_label]]</h3> 
    <p>[[!+fi.notes]]</p>
    
    <h3>[[+contact_label]]</h3>
    <p>[[!+fi.contact]]</p>
    <!--
    [[+contactEmail_label]]
    [[!+fi.contact_email]]
    [[+contactPhone_label]]
    [[!+fi.contact_phone]]
    -->
</form>