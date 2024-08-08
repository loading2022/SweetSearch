$(document).ready(function(){
  var storedSelectedType = localStorage.getItem("selectedType");
  if (storedSelectedType) {
      $(".dessert_tab li[data-type='" + storedSelectedType + "']").addClass("dessert_tab_active");
  }
  $("#index-zone-choice").click(function(){
    $(".style-choice-dropdown").removeClass("style-choice-dropdown-active");
    $(".zone-choice-dropdown").toggleClass("zone-choice-dropdown-active");
  });

  $("#index-style-choice").click(function(){
    $(".zone-choice-dropdown").removeClass("zone-choice-dropdown-active");
    $(".style-choice-dropdown").toggleClass("style-choice-dropdown-active");
  });

  $("#no-visited-input").click(function(){
    $("#no-visited-label").toggleClass("no-visited-active");
  })

  $("#four-star-input").click(function(){
    $("#four-star-label").toggleClass("four-star-active");
  })

  $(".dessert_tab li").click(function(){
    $(this).siblings().removeClass("dessert_tab_active");
    $(this).addClass("dessert_tab_active");
    var selectedType = $(this).data('type');
    $("#selectedType").val(selectedType);
    localStorage.setItem("selectedType", selectedType);
    $("#typeForm").submit();
  })
  
});

/*opentime switch*/
// 获取开关和显示状态的元素
const toggles = document.querySelectorAll('.toggle');

// 遍歷 NodeList，為每個開關設置事件監聽器
toggles.forEach(function(toggle) {
  const day = toggle.id.split('-')[0];
  const status = document.querySelector('#' + day + '-open-status');

  // 設置事件監聽器
  toggle.addEventListener('change', function() {
    // 根據開關的狀態更新相應的 <span> 元素的內容
    if (toggle.checked) {
      status.textContent = '今日有營業';
    } else {
      status.textContent = '未營業';

    }
  });
});

function toggleTimeFields(checkbox) {
    var dayPrefix = checkbox.id;
    var openTimeField = document.getElementsByName(dayPrefix + '_open_time')[0];
    var closeTimeField = document.getElementsByName(dayPrefix + '_close_time')[0];

    if (checkbox.checked) {
        // Checkbox is checked, enable time fields
        openTimeField.removeAttribute('disabled');
        closeTimeField.removeAttribute('disabled');
    } else {
        // Checkbox is unchecked, clear and disable time fields
        openTimeField.value = '';
        closeTimeField.value = '';
        openTimeField.setAttribute('disabled', 'disabled');
        closeTimeField.setAttribute('disabled', 'disabled');
    }
}

function openCommentForm() {
  document.getElementById('commentFormOverlay').style.display = 'flex';
}

function closeCommentForm() {
  document.getElementById('commentFormOverlay').style.display = 'none';
}

function editCommentForm(){
  document.getElementById('commentEditFormOverlay').style.display = 'flex';
}

function closeEditCommentForm() {
  document.getElementById('commentEditFormOverlay').style.display = 'none';
}
document.querySelector('.comment-submit').addEventListener('click', function (event) {
  // Check if a rating is selected
  if (document.getElementById('selectedRating').value === '') {
      document.querySelector('.validation-message').style.display = 'block';
      event.preventDefault(); // Prevent form submission
  } else {
      document.querySelector('.validation-message').style.display = 'none';
  }
});

var ratingNumbers = document.querySelectorAll('.rating-number');
ratingNumbers.forEach(function (ratingNumber) {
  // Add click event listener to each element
  ratingNumber.addEventListener('click', function () {
    // Remove 'active' class from all elements
    ratingNumbers.forEach(function (element) {
      element.classList.remove('active');
    });

    // Toggle 'active' class for the clicked element
    ratingNumber.classList.add('active');
  });
});

function setRating(rating) {
  const ratingIcon = document.querySelector(`.rating-number[onclick="setRating(${rating})"]`);
  const selectedRatingInput = document.querySelector(`input[name="selected_rating"]`);
  selectedRatingInput.value = rating;
}

function setEditRating(rating) {
  const ratingIcon = document.querySelector(`.rating-number[onclick="setEditRating(${rating})"]`);
  const selectedRatingInput = document.querySelector(`input[name="selected_edit_rating"]`);
  selectedRatingInput.value = rating;
}

function submitForm(){
  document.querySelector(".comment-search>form").submit();
}


function deletionAlert(shopID){
  if (confirm("確定要刪除評論嗎？")) {
    window.location.href = "../comment/delete_comment.php?shop_id=" + shopID;
  } else {
    window.location.href = "../shop/shop_info.php?shop_id=" + shopID;
  }
}

function deletionFavorite(shopID){
  if (confirm("確定要取消收藏嗎？")) {
    window.location.href = "../favorite/deleteFavorite.php?id=" + shopID;
  }
}

function deletionUser(userID){
  console.log('hi');
  if (confirm("確定要刪除帳號嗎？")) {
    window.location.href = "../user/delete_userinfo.php?user_id=" + userID;
  } else {
    window.location.href = "../user/user_info.php?user_id=" + userID;
  }
}

function deletionShop(shopID){
  console.log('hi');
  if (confirm('確定要刪除此店家嗎？')) {
    window.location.href = '../shop/delete_shop.php?id=' + shopID;
  } else {
    window.location.href = '../manager_index.php';
  }
}

function deletionDess(shopID,dessID){
  if (!isNewRowBeingAdded) {
  console.log('hi');
  if (confirm('確定要刪除此甜點嗎？')) {
    window.location.href = '../dessert/delete_dess.php?shop_id=' + shopID + '&dess_id=' + dessID;
  } else {
    window.location.href = '../dessert/manager_dessert_index.php?shop_id=' + shopID;
  }
}}

function setIndexZone(){
  var selected_zone=document.querySelectorAll('.zone-choice-dropdown>li')
  var indexSelectedZone=document.getElementById(indexSelectedZone);
  indexSelectedZone.value=selected_zone;
  console.log(indexSelectedZone);
  
}

var selectElement;

function getType(callback,oriType) {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', '../dessert/select_type.php', true);

  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      // 在這裡處理從後端返回的 desstype_ID 和 desstype_Name 列表
      var responseData = JSON.parse(xhr.responseText);
      // 將資料傳遞給回調函數
      callback(responseData,oriType);
    }
  };

  xhr.send();
}

function handleDesstypeOptions(desstypeOptions,oriType) {
  // 添加 desstype 选项到下拉框
  desstypeOptions.forEach(function(option) {
    var optionElement = document.createElement('option');
    optionElement.value = option['desstype_ID'];
    optionElement.innerText = option['desstype_Name'];
    selectElement.appendChild(optionElement);
    // console.log(option['desstype_Name']);
    if (oriType === option['desstype_Name']) {
      optionElement.selected = true;
      // console.log("find");
    }
  });
}

function modifyRow(shopID, dessID,modifyButton) {
  var rowElement = document.getElementById('row_' + shopID + '_' + dessID);
  var textElements = rowElement.querySelectorAll('.text-element');
  // var textElements_2 = textElements;

  // 創建一個取消按鈕
  var cancelButton = document.createElement('button');
  cancelButton.className = 'search-button cancel-button';
  cancelButton.innerHTML = '取消';
  //按下'取消'按鈕
  cancelButton.onclick = function() {
    var rowElement_2 = document.getElementById('row_' + shopID + '_' + dessID);
    var textElements_2 = rowElement_2.querySelectorAll('.text-element');
    
    textElements_2.forEach(function(textElement,index) {
      var originalTextElement = document.createElement('td');
      originalTextElement.className = 'text-element';
      // console.log(textElement.innerText);
      if (index === 2) { // 如果是下拉選單的列
        // console.log(oriT);
        // originalTextElement.innerText = selectElement.options[selectElement.selectedIndex].innerText;
        originalTextElement.innerText=oriT;
    } else {
        originalTextElement.innerText = ori[index]; // 內容保持不變
    }
      // originalTextElement.innerText = ori[index]; // 內容保持不變
      textElement.parentNode.replaceChild(originalTextElement, textElement);

    });

   
    // 還原 "送出" 按鈕為 "修改" 按鈕
    modifyButton.innerHTML = '修改';
    modifyButton.onclick = function() {
      modifyRow(shopID, dessID);
    };

    // 移除取消按鈕
    cancelButton.parentNode.removeChild(cancelButton);
  };

  // 插入取消按鈕
  rowElement.appendChild(cancelButton);

  // 創建一個輸入框元素的陣列
  var inputElements = [];
  var ori=[];
  var oriT;
  
  textElements.forEach(function (textElement, index) {
    var inputElement;
  
    if (index === 2) { // 這是 desstype_Name 的索引，對應到你的 PHP 代碼中的 "desstype_Name"
      selectElement = document.createElement('select'); // 移除 var 以便該變數在外部可見
      selectElement.className = 'text-element';
      oriT=textElement.innerText;
      getType(handleDesstypeOptions,textElement.innerText);

      
      inputElement = selectElement;
      ori.push(selectElement.value);
    } else {
      inputElement = document.createElement('input');
      inputElement.type = 'text';
      inputElement.value = textElement.innerText;
      ori.push(inputElement.value);
    }
  
    // 創建一個獨立的 <td>，並將輸入框或下拉框加入其中
    var tdElement = document.createElement('td');
    tdElement.className = 'text-element';
    tdElement.appendChild(inputElement);
  
    // 替換原本的文字元素
    textElement.parentNode.replaceChild(tdElement, textElement);
  
    // 將 inputElement 加入 inputElements 陣列
    inputElements.push(inputElement);
  });
  
  console.log(inputElements);
  console.log(ori);



  // 將 "修改" 按鈕變成 "送出" 按鈕
  modifyButton = rowElement.querySelector('.modify-button');
  modifyButton.innerHTML = '送出';
  // 按下送出按鈕後
  modifyButton.onclick = function() {
    if (!isNewRowBeingAdded) {
// console.log(inputElements.length); ->3
      // 在這裡執行送出的相應邏輯，例如使用 AJAX 發送到後端
      var newValues = inputElements.map(function(inputElement,index) {
        if(index===2){
          // console.log(inputElement.options[inputElement.selectedIndex].innerText);
          return inputElement.options[inputElement.selectedIndex].innerText;
        }
        else{
          return inputElement.value;
        }
        
      });

    // 使用 AJAX 發送 POST 請求到後端
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../dessert/adjust_dess.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        // 在這裡處理後端返回的任何數據或確認消息
        // console.log(xhr.responseText);
        console.log('test');
      }
    }

    var data = 'shopID=' + encodeURIComponent(shopID) +
               '&dessID=' + encodeURIComponent(dessID) +
               '&newValues=' + encodeURIComponent(JSON.stringify(newValues));
    xhr.send(data);
    
    
    var rowElement_3 = document.getElementById('row_' + shopID + '_' + dessID);
    var textElements_3 = rowElement_3.querySelectorAll('.text-element');
    textElements_3.forEach(function(textElement,index) {
      var originalTextElement = document.createElement('td');
      originalTextElement.className = 'text-element';
      if (inputElements[index]) {
        if (index === 2) { // 如果是下拉選單的列
            console.log(oriT);
            originalTextElement.innerText = inputElements[index].options[inputElements[index].selectedIndex].innerText;
        } else {
            originalTextElement.innerText = inputElements[index].value; // 內容保持不變
        }
    } else {
        console.error('Input element at index ' + index + ' is undefined or does not exist.');
    }
      textElement.parentNode.replaceChild(originalTextElement, textElement);

    });


    // 還原 "送出" 按鈕為 "修改" 按鈕
    modifyButton.innerHTML = '修改';
    modifyButton.onclick = function() {
      modifyRow(shopID, dessID,modifyButton);
    };

    // 移除取消按鈕
    cancelButton.parentNode.removeChild(cancelButton);
    }
  };
}

var isNewRowBeingAdded = true;

function addRow(shopID) {
  // 獲取表格
  var table = document.getElementById('dessert-table');
  
  var lastRow = table.rows[table.rows.length - 1];
  if (lastRow.cells[0].querySelector('input')) {
    var firstCellValue = lastRow.cells[0].querySelector('input').value;
  } 
  else if (lastRow.cells[0].textContent.trim() !== "店家ID") {
    var firstCellValue = lastRow.cells[0].innerText;
  }
  else{
    var firstCellValue=null;
  }
  var lastNumber = parseInt(firstCellValue.substr(2, 2), 10);

  if(!isNaN(lastNumber)){
    var newNumber = lastNumber + 1;
  }
  else{
    var newNumber = 1;
  }

  var newID = 'd_' + ('00' + newNumber).slice(-2);


  // 創建新行
  var newRow = table.insertRow(-1);
  newRow.id = 'row_'+shopID+'_' + newID;

  //自動配給ID
  var cell = newRow.insertCell(0);
  cell.innerText=newID;

  // 建立單元格，並為每個單元格添加一個空白的文字框

  var cell = newRow.insertCell(1);
  var input = document.createElement('input');
  input.type = 'text';
  cell.className = 'text-element';
  cell.appendChild(input);
  var cell = newRow.insertCell(2);
  var input = document.createElement('input');
  input.type = 'number';
  cell.className = 'text-element';
  cell.appendChild(input);
  // 下拉式選單
  var cell = newRow.insertCell(3);
  selectElement = document.createElement('select'); // 移除 var 以便該變數在外部可見
  selectElement.className = 'text-element';
  // oriT=textElement.innerText;
  getType(handleDesstypeOptions,"其他");
  cell.className = 'text-element';
  cell.appendChild(selectElement);


  // 建立「送出」和「刪除」按鈕
  var modifyCell = newRow.insertCell(4);
  var modifyButton1 = document.createElement('button');
  modifyButton1.className = 'search-button modify-button';
  modifyButton1.innerText = '送出';
  modifyCell.appendChild(modifyButton1);
  //按下送出
// 添加送出按鈕的點擊事件處理程序
modifyButton1.addEventListener('click', function handleModifyButtonClick() {
    // 找到被點擊的按鈕所在的行
    var noDessElement = document.querySelector('.noDess');

    // 检查是否找到元素
    if (noDessElement) {
      // 如果找到了，删除该元素
     noDessElement.parentNode.removeChild(noDessElement);
    }
    var rowToModify = modifyButton1.parentNode.parentNode;

    // 获取需要发送到服务器的数据
    // var dessID = rowToModify.cells[0].querySelector('input').value;
    var dessID = rowToModify.cells[0].innerText;
    var name = rowToModify.cells[1].querySelector('input').value;
    var price = rowToModify.cells[2].querySelector('input').value;
    // var type = rowToModify.cells[3].querySelector('input').value;
    var type = Array.from(selectElement.options).find(option => option.selected).innerText;

    // 使用 AJAX 发送数据到服务器端的 PHP 脚本
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../dessert/create_dess.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // 处理从服务器返回的响应
            console.log(xhr.responseText);
        }
    };

    // 组装要发送的数据
    var data = 'shop_id=' + shopID +
               '&dess_id=' + dessID +
               '&dess_name=' + name +
               '&dess_price=' + parseInt(price) +
               '&dess_type=' + type;

    // 发送数据
    xhr.send(data);
    
    rowToModify.cells[1].innerText = name;
    rowToModify.cells[2].innerText = price;
    rowToModify.cells[3].innerText = type;

    // 還原 "送出" 按鈕為 "修改" 按鈕
    modifyButton1.innerHTML = '修改';
    modifyButton1.onclick = function () {
      modifyRow(shopID, dessID, modifyButton1); // 傳遞 modifyButton1 給 modifyRow 函數
    };

    // 移除事件監聽器，使其不再觸發
    modifyButton1.removeEventListener('click', handleModifyButtonClick);
    isNewRowBeingAdded = false; // 將 isNewRowBeingAdded 設置為 false
  // });
  deleteButton.onclick = function () {
    deletionDess(shopID, dessID, deleteButton); // 傳遞 modifyButton1 給 modifyRow 函數
  };
  // 移除事件監聽器，使其不再觸發
  deleteButton.removeEventListener('click', handleModifyButtonClick1);
});

  

  var deleteCell = newRow.insertCell(5);
  var deleteButton = document.createElement('button');
  deleteButton.type = 'submit';
  deleteButton.className = 'delete-button';
  deleteButton.innerText = '刪除';
  deleteCell.appendChild(deleteButton);

  deleteButton.addEventListener('click', function handleModifyButtonClick1() {
      // 找到被點擊的按鈕所在的行
      var rowToDelete = deleteButton.parentNode.parentNode;
      // 從表格中刪除該行
      table.deleteRow(rowToDelete.rowIndex);
 

});


}

var isNewTypeBeingAdded=true;

//甜點種類
function deletionDessType(ID){
  if (!isNewTypeBeingAdded) {
  console.log('hi');
  if (confirm('確定要刪除此甜點種類嗎？')) {
      window.location.href = './delete_dessType.php?id=' + ID;
  } else {
      window.location.href = './manager_dessType_index.php';
  }   
}
}

function adjustType(dessTypeID, modifyButton){
  var rowElement = document.getElementById('row_' + dessTypeID);
  var textElements = rowElement.querySelectorAll('.text-element');
  // var textElements_2 = textElements;

  // 創建一個取消按鈕
  var cancelButton = document.createElement('button');
  cancelButton.className = 'search-button cancel-button';
  cancelButton.innerHTML = '取消';
  //按下'取消'按鈕
  cancelButton.onclick = function() {
    var rowElement_2 = document.getElementById('row_' + dessTypeID);
    var textElements_2 = rowElement_2.querySelectorAll('.text-element');
    
    textElements_2.forEach(function(textElement,index) {
      var originalTextElement = document.createElement('td');
      originalTextElement.className = 'text-element';
      // console.log(textElement.innerText);
      originalTextElement.innerText = ori[index]; // 內容保持不變
      // originalTextElement.innerText = ori[index]; // 內容保持不變
      textElement.parentNode.replaceChild(originalTextElement, textElement);

    });

   
    // 還原 "送出" 按鈕為 "修改" 按鈕
    modifyButton.innerHTML = '修改';
    modifyButton.onclick = function() {
      adjustType(dessTypeID);
    };

    // 移除取消按鈕
    cancelButton.parentNode.removeChild(cancelButton);
  };

  // 插入取消按鈕
  rowElement.appendChild(cancelButton);

  // 創建一個輸入框元素的陣列
  var inputElements = [];
  var ori=[];
  var oriT;
  
  textElements.forEach(function (textElement, index) {
    var inputElement;
  
      inputElement = document.createElement('input');
      inputElement.type = 'text';
      inputElement.value = textElement.innerText;
      ori.push(inputElement.value);
  
    // 創建一個獨立的 <td>，並將輸入框或下拉框加入其中
    var tdElement = document.createElement('td');
    tdElement.className = 'text-element';
    tdElement.appendChild(inputElement);
  
    // 替換原本的文字元素
    textElement.parentNode.replaceChild(tdElement, textElement);
  
    // 將 inputElement 加入 inputElements 陣列
    inputElements.push(inputElement);
  });
  
  console.log(inputElements);
  console.log(ori);



  // 將 "修改" 按鈕變成 "送出" 按鈕
  modifyButton = rowElement.querySelector('.modify-button');
  modifyButton.innerHTML = '送出';
  // 按下送出按鈕後
  modifyButton.onclick = function() {
    if (!isNewRowBeingAdded) {
// console.log(inputElements.length); ->3
      // 在這裡執行送出的相應邏輯，例如使用 AJAX 發送到後端
      var newValues = inputElements.map(function(inputElement,index) {
          return inputElement.value;
      });

    // 使用 AJAX 發送 POST 請求到後端
    var xhr = new XMLHttpRequest();
    xhr.open('POST', './adjust_dessType.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        // 在這裡處理後端返回的任何數據或確認消息
        // console.log(xhr.responseText);
        console.log('test');
      }
    }

    var data = 'dessTypeID=' + encodeURIComponent(dessTypeID) +
               '&newValues=' + encodeURIComponent(JSON.stringify(newValues));
    xhr.send(data);
    
    
    var rowElement_3 = document.getElementById('row_' + dessTypeID);
    var textElements_3 = rowElement_3.querySelectorAll('.text-element');
    textElements_3.forEach(function(textElement,index) {
      var originalTextElement = document.createElement('td');
      originalTextElement.className = 'text-element';
      if (inputElements[index]) {
            originalTextElement.innerText = inputElements[index].value; // 內容保持不變
        
    } else {
        console.error('Input element at index ' + index + ' is undefined or does not exist.');
    }
      textElement.parentNode.replaceChild(originalTextElement, textElement);

    });


    // 還原 "送出" 按鈕為 "修改" 按鈕
    modifyButton.innerHTML = '修改';
    modifyButton.onclick = function() {
      adjustType(dessTypeID, modifyButton);
    };

    // 移除取消按鈕
    cancelButton.parentNode.removeChild(cancelButton);
    }
  };
}

function addDessType(){
  // 獲取表格
  var table = document.getElementById('dessType-table');
  
  var lastRow = table.rows[table.rows.length - 1];
  if (lastRow.cells[0].querySelector('input')) {
    var firstCellValue = lastRow.cells[0].querySelector('input').value;
  } 
  else if (lastRow.cells[0].textContent.trim() !== "desstype_ID") {
    var firstCellValue = lastRow.cells[0].innerText;
  }
  else{
    var firstCellValue=null;
  }
  var lastNumber = parseInt(firstCellValue.substr(3, 2), 10);

  if(!isNaN(lastNumber)){
    var newNumber = lastNumber + 1;
  }
  else{
    var newNumber = 1;
  }
  var newID = 'dt_' + ('00' + newNumber).slice(-2);


  // 創建新行
  var newRow = table.insertRow(-1);
  newRow.id = 'row_' + newID;

  //自動配給ID
  var cell = newRow.insertCell(0);
  cell.innerText=newID;

  // 建立單元格，並為每個單元格添加一個空白的文字框
      var cell = newRow.insertCell(1);
      var input = document.createElement('input');
      input.type = 'text';
      cell.className = 'text-element';
      cell.appendChild(input);


  // 建立「送出」和「刪除」按鈕
  var modifyCell = newRow.insertCell(2);
  var modifyButton1 = document.createElement('button');
  modifyButton1.className = 'search-button modify-button';
  modifyButton1.innerText = '送出';
  modifyCell.appendChild(modifyButton1);
  //按下送出
// 添加送出按鈕的點擊事件處理程序
modifyButton1.addEventListener('click', function handleModifyButtonClick() {
    // 找到被點擊的按鈕所在的行
    var noDessElement = document.querySelector('.noDess');

    // 检查是否找到元素
    if (noDessElement) {
      // 如果找到了，删除该元素
     noDessElement.parentNode.removeChild(noDessElement);
    }
    var rowToModify = modifyButton1.parentNode.parentNode;

    // 获取需要发送到服务器的数据
    // var dessID = rowToModify.cells[0].querySelector('input').value;
    var dessTypeID = rowToModify.cells[0].innerText;
    var name = rowToModify.cells[1].querySelector('input').value;

    // 使用 AJAX 发送数据到服务器端的 PHP 脚本
    var xhr = new XMLHttpRequest();
    xhr.open('POST', './create_dessType.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // 处理从服务器返回的响应
            console.log(xhr.responseText);
        }
    };

    // 组装要发送的数据
    var data = 'desstype_id=' + dessTypeID +
               '&desstype_name=' + name;

    // 发送数据
    xhr.send(data);
    
    rowToModify.cells[1].innerText = name;

    // 還原 "送出" 按鈕為 "修改" 按鈕
    modifyButton1.innerHTML = '修改';
    modifyButton1.onclick = function () {
      adjustType(dessTypeID, modifyButton1); // 傳遞 modifyButton1 給 modifyRow 函數
    };

    // 移除事件監聽器，使其不再觸發
    modifyButton1.removeEventListener('click', handleModifyButtonClick);
    isNewTypeBeingAdded = false; // 將 isNewRowBeingAdded 設置為 false
  // });
  deleteButton.onclick = function () {
    deletionDessType(dessTypeID, deleteButton); // 傳遞 modifyButton1 給 modifyRow 函數
  };
  // 移除事件監聽器，使其不再觸發
  deleteButton.removeEventListener('click', handleModifyButtonClick1);
});

  

  var deleteCell = newRow.insertCell(3);
  var deleteButton = document.createElement('button');
  deleteButton.type = 'submit';
  deleteButton.className = 'delete-button';
  deleteButton.innerText = '刪除';
  deleteCell.appendChild(deleteButton);

  deleteButton.addEventListener('click', function handleModifyButtonClick1() {
      // 找到被點擊的按鈕所在的行
      var rowToDelete = deleteButton.parentNode.parentNode;
      // 從表格中刪除該行
      table.deleteRow(rowToDelete.rowIndex);

});

}








