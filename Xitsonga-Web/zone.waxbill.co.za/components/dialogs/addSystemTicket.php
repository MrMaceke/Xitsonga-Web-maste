<?php

?>
<div class ='addTicketDialog' style ="visibility: hidden">
    <br/><br/>
    <div class="module" style ="background: #F8F5F0">
        <div class="module-head"><h3>Add Ticket</h3></div>
        <div class="module-body">
            <div class ='error'></div>
            <input  id = "clientId" type="hidden" class="form-control span8" value ="{{client.client.userID}}"/>
            <form method="get" class="form-horizontal row-fluid">
                <div class="control-group">
                    <label class="control-label">Project Id</label>
                    <div class="controls">
                       <input  id = "projectId" type="text" class="form-control span8" placeholder="Optional"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Describe</label>
                    <div class="controls">
                         <textarea  id = "ticketDescription" class="form-control span8" placeholder="Description the issue..."></textarea>
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
                        <button class="btn btn-primary" type="submit" onclick="addNewTicket(event)">Add Ticket</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>