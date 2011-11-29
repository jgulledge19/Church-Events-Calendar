<ul class="plan">
    <li class="full">
        <p class="small_font [[!+fi.error.edit_repeat:notempty=`formError`]]" style="margin:0;padding:0;">[[+deleteRepeat_heading]]</p>
        <ul>
            <li class="autoWidth spaceRight">
                <input name="edit_repeat" type="radio" value="all" [[!+fi.edit_repeat:FormItIsChecked=`all`]] id="rd_edit_repeat_all" class="radio" /> 
                <label for="rd_edit_repeat_all">[[+deleteRepeatAll_label]]</label>
            </li>
            <!-- Not yet a feature
            <li class="autoWidth spaceRight">
                <input name="edit_repeat" type="radio" value="unchanged" id="rd_edit_repeat_un" class="radio"  /> 
                <label for="rd_edit_repeat_un">Overide only unchanged instances</label>
            </li> -->
            <li class="autoWidth spaceRight">
                <input name="edit_repeat" type="radio" value="instance" [[!+fi.edit_repeat:FormItIsChecked=`instance`]] id="rd_edit_repeat_single" class="radio" /> 
                <label for="rd_edit_repeat_single">[[+deleteRepeatSingle_label]]</label>
            </li>
        </ul>
    </li>
</ul>
