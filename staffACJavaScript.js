/**
    $(".nav-btn").click(function(){
        if( strcmp(acPageName,"student") == 0){
            $(".nav-btn").css("background-color","");
            $(this).css("background-color","rgba(185, 224, 223, 0.356)");
        }
        else if(strcmp(acPageName,"staff")){
            $(".nav-btn").css("background-color","");
            $(this).css("background-color","rgba(19, 20, 20, 0.356)");
        }
        
    });
*/

$(document).ready(function(){
    $(".toast").toast('show');
});

$(".nav-btn").click(function(){
    $(".nav-btn").css("background-color","");
    $(this).css("background-color","black");
});

$(".myRequest").click(function(){
    var formClassName = "."+$(this).attr("id");
    $(formClassName).submit();
});

$("#logOut_false").click(function(){
    $(".logOut_btn").hide();
    $("#logOutTxt").html("<p>Thank you!</p>Enjoy our services.");
});

$(".sendFile").click(function(){
    $("#formFile").click();
    $(".bi-link-45deg").attr("fill","#1d1616");
});

$("#sendComment").click(function(){
    var $commentMsg = $("#commentMsg").val();
    var $commentFile = $("#commentFile").val();
    if($commentMsg || $commentFile){
        $("#commentForm").submit();
    }
});