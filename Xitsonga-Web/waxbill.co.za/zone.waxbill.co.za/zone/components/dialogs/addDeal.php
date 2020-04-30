<?php

?>
<div class ='addNewDealDialog' style ="visibility: hidden">
    <br/><br/>
    <div class="module" style ="background: #F8F5F0">
        <div class="module-head"><h3>Add Deal</h3></div>
        <div class="module-body">
            <div class ='error'></div>
            <form method="get" class="form-horizontal row-fluid">
                <div class="control-group">
                    <label class="control-label">Deal Name</label>
                    <div class="controls">
                       <input  id = "dealName" type="text" class="form-control span8" placeholder="Deal Name"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Deal Description</label>
                    <div class="controls">
                         <textarea  id = "description" class="form-control span8" placeholder="Description the deal"></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Deal Price</label>
                    <div class="controls">
                       <input  id = "dealPrice" type="text" class="form-control span8" placeholder="Deal Price, e.g 14000"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Start Date</label>
                    <div class="controls">
                        <input  id = "startDate" type="text" class="form-control span8 datePicker" placeholder="Start Date"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Due Date</label>
                    <div class="controls">
                        <input  id = "dueDate" type="text" class="form-control span8 datePicker" placeholder="Due Date"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"></label>
                    <div class="controlso">
                        <button class="btn btn-danger" type="submit" onclick="reloadPage(event)">Cancel</button>
                        <button class="btn btn-primary" type="submit" onclick="addNewDeal(event)">Add Deal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>