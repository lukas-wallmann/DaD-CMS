<script>
var obj = {
    name: "Simon",
    age: "20",
    clothing: {
        style: "simple",
        hipster: false
    }
}

for(var propt in obj){
    alert(propt + ': ' + obj[propt]);
}
</script>
