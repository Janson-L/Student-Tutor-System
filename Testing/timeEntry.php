<html>

    <script src="http://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.js"></script>
    <link href="http://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.css" rel="stylesheet"/>

    <script>
      var timepicker = new TimePicker('time', {
        lang: 'en',
        theme: 'dark'
      });
      timepicker.on('change', function(evt) {
        
        var value = (evt.hour || '00') + ':' + (evt.minute || '00');
        evt.element.value = value;

      });
    </script>

<div>
  <input type="text" id='time'>
</div>
</html>