<!-------------------footer----------------------->
<div id="footer">
  <div id="footer_wapper">
    <div id="section">
      <p class="cop1">
        <?php
        echo $footer_txt1;
        ?>
      </p>
    </div>
    <div id="section" align="right">
      <p class="cop1">
        <?php
        echo $footer_txt2;
        ?>
        <a <?php
            echo 'href="' . $footer_link . '"';
            ?> target="_blank" id="footer_links">
          <?php
          echo $footer_lintxt;
          ?>
        </a>
      </p>
    </div>
  </div>
</div>
<!-------------------footer----------------------->

<!---------------------mypopup popupdown--------------------->
<script>
  function mypopupfun() {
    document.getElementById("mypopup").classList.toggle("show");
  }
  // Close the dropdown if the user clicks outside of it
  window.onclick = function(event) {
    if (!event.target.matches('.dropbtnpop')) {
      var dropdowns = document.getElementsByClassName("popupdown-content");
      var i;
      for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
          openDropdown.classList.remove('show');
        }
      }
    }
  }
</script>
<!---------------------------------------------------------------->

<script>
  let aside = document.querySelector('#menu_war');
  let icon = document.querySelector('#toggle_div');
  let li = aside.getElementsByClassName('nav-item');
  icon.onclick = () => {
    aside.classList.toggle('expand');
  }
  for (let i of li) {
    i.onclick = activeLi;
  }

  function activeLi() {
    const list = Array.from(li);
    list.forEach(e => e.classList.remove('active'));
    this.classList.add('active');
  }
</script>

<!------------------------toggle btn------------------------------>
<script>
  function togglebtn(x) {
    x.classList.toggle("change");
  }
</script>
<!--------------------------------------------------------------->

<!------------------------Add Complain Form Validation------------------------------>
<script>
  // Automatically set the current date
  document.getElementById('current-date').value = new Date().toISOString().split('T')[0];

  const input = document.querySelector('input[type="file"]');
  const userInfo = document.getElementById('info');

  input.addEventListener('change', () => {
    validateSize();
  })

  // validate size
  function validateSize() {
    const file = input.files[0];
    if (!file) {
      return false;
    }

    const limit = 1000;
    // const size = file.size / 1024;
    let size = 0;
    for (item of input.files) {
      size += item.size / 1024;
    }

    if (size > limit) {
      const errMessage = `File(s) too big: ${(size / 1000).toFixed(1)} MB`;
      userInfo.textContent = errMessage;
      return false;
    } else {
      userInfo.textContent = "";
      return true;
    }
  }

  // Add form validation
  document.getElementById('complainForm').addEventListener('submit', function(event) {
    var assetno = document.getElementById('assetnoInput').value;
    var regOffice = document.getElementById('regional_office').value;
    var empid = document.getElementById('empid').value;
    var subject = document.getElementById('subject').value;

    // Validate dropdowns
    if (regOffice === 'select') {
      alert('Please select a valid Regional Office.');
      event.preventDefault(); // Prevent form submission
      return;
    }

    // Validate required fields
    if (!assetno.trim()) {
      alert('Please enter a valid Asset No.');
      event.preventDefault(); // Prevent form submission
      return;
    }

    if (!empid.trim()) {
      alert('Please enter a valid Employee ID.');
      event.preventDefault(); // Prevent form submission
      return;
    }

    if (!subject.trim()) {
      alert('Please enter your observations.');
      event.preventDefault(); // Prevent form submission
      return;
    }

    // Validate file size
    if (!validateSize()) {
      alert('Please select image size less than 1MB.');
      event.preventDefault(); // Prevent form submission
      return;
    }

  });

  function openPopup() {
    let popup = document.getElementById("popup");
    popup.classList.add("open-popup");
    document.getElementById("overlay").style.display = "block";
  }

  function closePopup() {
    let popup = document.getElementById("popup");
    popup.classList.remove("open-popup");
    document.getElementById("overlay").style.display = "none";

    // Clear all form fields
    document.getElementById('complainForm').reset();

    // Reset the date field manually since it's readonly and won't reset automatically
    document.getElementById('current-date').value = new Date().toISOString().split('T')[0];

    // Clear error message
    userInfo.textContent = "";

    // Redirect to home page after closing the popup
    window.location.href = "index.php?page=home";
  }
</script>
<!----------------------------------------------------------------------->

<!------------------------Add complain form auto suggestion box------------------------------>
<script>
  const assetnoInput = document.getElementById('assetnoInput');
  const suggestionBox = document.getElementById('suggestionBox');
  const serialnoInput = document.getElementById('serialno');

  assetnoInput.addEventListener('input', function() {
    const query = assetnoInput.value;
    if (query.length > 1) { // Start searching after user types at least 2 characters
      fetch(`include_ajaxs/search_assets.php?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
          suggestionBox.innerHTML = ''; // Clear previous suggestions
          if (data.length > 0) {
            suggestionBox.style.display = 'block'; // Show the suggestion box
            data.forEach(asset => {
              const listItem = document.createElement('li');
              listItem.textContent = asset.asset_no;
              listItem.addEventListener('click', function() {
                assetnoInput.value = asset.asset_no; // Set input value to the selected asset
                suggestionBox.style.display = 'none'; // Hide suggestions
                fetchSerialNumber(asset.asset_no);
              });
              suggestionBox.appendChild(listItem);
            });
          } else {
            suggestionBox.style.display = 'none'; // Hide if no suggestions
          }
        })
        .catch(error => {
          console.error('Error fetching asset numbers:', error);
          suggestionBox.style.display = 'none'; // Hide on error
        });
    } else {
      suggestionBox.style.display = 'none'; // Hide if input is too short
    }
  });

  // Fetch serial number based on the selected asset ID
  function fetchSerialNumber(assetNo) {
    fetch(`include_ajaxs/get_serial_number.php?asset_no=${encodeURIComponent(assetNo)}`)
      .then(response => response.json())
      .then(data => {
        if (data && data.serial_no) {
          serialnoInput.value = data.serial_no; // Auto-fill the serial number
        } else {
          serialnoInput.value = ''; // Clear if no serial number found
        }
      })
      .catch(error => {
        console.error('Error fetching serial number:', error);
      });
  }

  // Hide suggestions if user clicks outside
  document.addEventListener('click', function(event) {
    if (!assetnoInput.contains(event.target) && !suggestionBox.contains(event.target)) {
      suggestionBox.style.display = 'none';
    }
  });
</script>
<!----------------------------------------------------------------------->

</body>

</html>