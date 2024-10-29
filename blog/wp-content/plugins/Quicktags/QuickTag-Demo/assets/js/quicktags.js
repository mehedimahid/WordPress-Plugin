QTags.addButton('qtsd-button-two','JS', qtsd_button_two);
function qtsd_button_two(){
    var name = prompt("What is your name?")
    var text = 'Hello '+ name;
    QTags.insertContent(text);
}