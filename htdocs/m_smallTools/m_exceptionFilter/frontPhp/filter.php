<html>
    <head>
        <meta charset="utf-8"/>
        <script src="../../../../js-base/jquery.min.js"></script>
        <?php
            include "api.php";
        ?>
    </head>
    <body>
       <h3>异常过滤</h3>
       <br/>
       <div>
           <label>机器:</label>
           <input type="text" id="hostName" value='qt104'/>
           <label>eg: qt104</label>
       </div>
       <div>
           <label>指定文件:</label>
           <input type="text" id="path" size="80"/>
           <label>eg: /disk3/lihy/union/ead-lr-ctr-feature-impr/logs/stderr</label>
       </div>
       <div>
           <label>白名单：</label>
           <select id="whiteList">
               <?php
                   $whitelistNames = getListNames();
                   sort($whitelistNames);
                   foreach (array_reverse($whitelistNames) as $whitelistName) {
                       echo "<option>$whitelistName</option>";
                   }
               ?>
           </select>
       </div>
       <!-- ################### view result div ############################# -->
       <label>结果：（显示结果为：行号 || 异常）</label>
       <div id="result_list_l=div">
       <textarea id="result" cols="160" rows="10" disabled="disabled"></textarea>
       </div>
       <label>status:</label><label id="status">当文件较大时，请耐心等待片刻。</label>
       <div>
           <br/>
           <button type="button" id="process_btn">开始过滤</button>
       </div>

       <script src="../js/filter.php.js"></script> 
    </body>
</html>
