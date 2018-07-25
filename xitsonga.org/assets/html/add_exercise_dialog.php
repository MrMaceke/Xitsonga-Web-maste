<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div id="openExerciseModal" class="modalbg modalbg_2">
  <div class="dialog">
    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" title="Close" class="close">X</a>
    <br/><span style ="font-size: 18px;text-transform:uppercase">* Free license contribution - Create Exercise</span>
    <hr>    
    <form class ='basic_form add_exercise_form'>
        <table class ='dialog_table'>
            <tr>
                <td style ="width:40%">
                	<span style ="font-size: 15px;text-transform:uppercase">Title</span><br/>
                	<span style ="font-size: 11px;">A title of the exercise</span>
               	</td>
                <td><textarea id ='title' placeholder="A title of the exercise" class="form-control" rows="2" cols="50"></textarea></td>
            </tr>
            <tr class ='other_input event_input'>
                <td>
                	<span style ="font-size: 15px;text-transform:uppercase">Translation</span><br/>
                	<span style ="font-size: 11px;">A description of the exercise</span>
                </td>
                <td><textarea class ="about_entity_val form-control" id ='description' placeholder="A description of the exercise" rows="4"></textarea></td>
            </tr>
            <tr>
                <td><div class ='loading_image'></div></td>
                <td>
                    <div class ="error"></div>
                    <input style ="margin: 5px" id ='add_exercise' class="btn btn-action" type="submit" value="Add Exercise">
                    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" class ='btn main_action cancel'>Cancel</a>
                </td>
            </tr>
        </table>
    </form>
  </div>
</div>


<div id="openEditExerciseModal" class="modalbg modalbg_2">
  <div class="dialog">
    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" title="Close" class="close">X</a>
    <br/><span style ="font-size: 18px;text-transform:uppercase">* Free license contribution - Create Exercise</span>
    <hr>    
    <form class ='basic_form edit_exercise_form'>
        <table class ='dialog_table'>
            <tr>
                <td style ="width:40%">
                    <span style ="font-size: 15px;text-transform:uppercase">Delete</span><br/>
                    <span style ="font-size: 11px;">Only for authorized personnel</span>
               	</td>
                <td>
                    <input id ='delete_exercise' style ="width:20px;height: 20px" type="checkbox">
                </td>
            </tr>
            <tr>
                <td style ="width:40%">
                	<span style ="font-size: 15px;text-transform:uppercase">Title</span><br/>
                	<span style ="font-size: 11px;">A title of the exercise</span>
               	</td>
                <td><textarea id ='title' placeholder="A title of the exercise" class="form-control" rows="2" cols="50"></textarea></td>
            </tr>
            <tr class ='other_input event_input'>
                <td>
                	<span style ="font-size: 15px;text-transform:uppercase">Translation</span><br/>
                	<span style ="font-size: 11px;">A description of the exercise</span>
                </td>
                <td><input type ="hidden" id = "exerciseID"/><textarea class ="about_entity_val form-control" id ='description' placeholder="A description of the exercise" rows="4"></textarea></td>
            </tr>
             <tr class ='other_input event_input'>
                <td>
                	<span style ="font-size: 15px;text-transform:uppercase">Published</span><br/>
                	<span style ="font-size: 11px;">Publish the exercise</span>
                </td>
                <td>
                    <select class ="form-control" id ="published">
                        <option value="0">In draft</option>
                        <option value="1">In production</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><div class ='loading_image'></div></td>
                <td>
                    <div class ="error"></div>
                    <input style ="margin: 5px" id ='update_exercise' class="btn btn-action" type="submit" value="Update Exercise">
                    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" class ='btn main_action cancel'>Cancel</a>
                </td>
            </tr>
        </table>
    </form>
  </div>
</div>

<div id="openQuestionsModal" class="modalbg modalbg_2">
  <div class="dialog">
    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" title="Close" class="close">X</a>
    <br/><span style ="font-size: 18px;text-transform:uppercase">* Free license contribution - Create Exercise</span>
    <hr>    
    <form class ='basic_form add_question_form'>
        <table class ='dialog_table'>
            <tr>
                <td style ="width:40%">
                	<span style ="font-size: 15px;text-transform:uppercase">Title</span><br/>
                	<span style ="font-size: 11px;">A title of the question</span>
               	</td>
                <td><input type ="hidden" id ="question_id" value ="<?php echo "$question_id";?>"/><textarea id ='title' placeholder="A title of the question" class="form-control" rows="2" cols="50"></textarea></td>
            </tr>
            <tr class ='other_input event_input'>
                <td>
                	<span style ="font-size: 15px;text-transform:uppercase">Type</span><br/>
                	<span style ="font-size: 11px;">Type of question</span>
                </td>
                <td>
                    <select id ='correct' class ='form-control'>
                        <option value="0">Radio</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><div class ='loading_image'></div></td>
                <td>
                    <div class ="error"></div>
                    <input style ="margin: 5px" id ='add_question' class="btn btn-action" type="submit" value="Add Question">
                    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" class ='btn main_action cancel'>Cancel</a>
                </td>
            </tr>
        </table>
    </form>
  </div>
</div>



<div id="openQuestionEditModal" class="modalbg modalbg_2">
  <div class="dialog">
    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" title="Close" class="close">X</a>
    <br/><span style ="font-size: 18px;text-transform:uppercase">* Free license contribution - Create Exercise</span>
    <hr>    
    <form class ='basic_form edit_question_form'>
        <table class ='dialog_table'>
            <tr>
                <td style ="width:40%">
                    <span style ="font-size: 15px;text-transform:uppercase">Delete</span><br/>
                    <span style ="font-size: 11px;">Only for authorized personnel</span>
               	</td>
                <td>
                    <input id ='delete_question' style ="width:20px;height: 20px" type="checkbox">
                </td>
            </tr>
            <tr>
                <td style ="width:40%">
                	<span style ="font-size: 15px;text-transform:uppercase">Title</span><br/>
                	<span style ="font-size: 11px;">A title of the question</span>
               	</td>
                <td><input type ="hidden" id ="question_id" value ="<?php echo "$question_id";?>"/><textarea id ='title' placeholder="A title of the question" class="form-control" rows="2" cols="50"></textarea></td>
            </tr>
            <tr class ='other_input event_input'>
                <td>
                	<span style ="font-size: 15px;text-transform:uppercase">Type</span><br/>
                	<span style ="font-size: 11px;">Type of question</span>
                </td>
                <td>
                    <select id ='correct' class ='form-control'>
                        <option value="0">Radio</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><div class ='loading_image'></div></td>
                <td>
                    <div class ="error"></div>
                    <input style ="margin: 5px" id ='update_question' class="btn btn-action" type="submit" value="Update Question">
                    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" class ='btn main_action cancel'>Cancel</a>
                </td>
            </tr>
        </table>
    </form>
  </div>
</div>


<div id="openAnswersModal" class="modalbg modalbg_2">
  <div class="dialog">
    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" title="Close" class="close">X</a>
    <br/><span style ="font-size: 18px;text-transform:uppercase" id ="answerTitle">* Free license contribution - Answers</span>
    <hr>    
    <form class ='basic_form add_answers_form'>
        <input type ="hidden" id ="question_id" value ="<?php echo "$question_id";?>"/>
        <table class ='dialog_table answers_table'>
            <tr class ='answer_row'>
                <td>
                    <span style ="font-size: 15px;text-transform:uppercase">Answer 1</span><br/>
                    <span style ="font-size: 11px;">Text of the answer</span>
               	</td>
                <td><textarea placeholder="A text of the answer" class="form-control" rows="1" cols="50"></textarea></td>
                <td>
                    <select class ='correct_answer form-control'>
                        <option value="0">Incorrect</option>
                        <option value="1">Correct</option>
                    </select>
                </td>
            </tr>
            <tr class ="lastTableRow">
                <td colspan="3">
                    <a class ='add_answer_row' style="font-size: 18px;font-weight: bold;margin-left:0px;color: green;display:inline-block;padding: 5px;cursor: pointer" title="Add Answer Row" alt ="Add Answer Row">+</a>
                    <a class ='remove_answer_row' style="font-size: 18px;font-weight: bold;margin-left:0px;color: red;display:inline-block;padding: 5px;cursor: pointer" title="Remove Answer Row" alt ="Remove Answer Row">-</a>
                </td>
            </tr>
            <tr>
                <td><div class ='loading_image'></div></td>
                <td>
                    <div class ="error"></div>
                    <input style ="margin: 5px" id ='update_answers' class="btn btn-action" type="submit" value="Process">
                    <a href="<?php echo $_SERVER["REQUEST_URI"]."#";?>" class ='btn main_action cancel'>Cancel</a>
                </td>
                <td></td>
            </tr>
        </table>
    </form>
  </div>
</div>