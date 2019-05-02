$(document).ready(function () {
   console.log("ready");
   jQuery(".todo_plugin").todo({serverURL: "server/actions.php"});
});