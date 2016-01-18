<table>
            <tr>
                <td colspan='2'>
                    <p style='color:blue; font-size:18px;'>Thông tin khách hàng</p>
                </td>
            </tr>
            <tr>
                <td>Ngày gửi: </td>
                <td><?php echo Date("Y-m-d H:i:s")?></td>
            </tr>
            <tr>
                <td>Họ tên khách hàng: </td>
                <td><?php echo $product['value']['name']?></td>
            </tr>
            <tr>
                <td>Giới tính: </td>
                <td><?php echo ($product['value']['gender']=="1") ?"Chi " : "And"?></td>
            </tr>
            <tr>
                <td>Điện thoại: </td>
               <td><?php echo $product['value']['phone']?></td>
            </tr>
            <tr>
                <td>Email: </td>
                <td><?php echo $product['value']['email']?></td>
            </tr>
            <tr>
                <td>Nghề nghiệp: </td>
                <td><?php echo ($product['value']['work']=="1") ?"Sinh viên " : "Đã đi làm"?></td>
            </tr>
        </table>
        <table  border='1'>
            <tr>
                <th width='100px;'>
                Sản phẩm
                </th>
                <th width='80px;'>
                Hình ảnh
                </th>
                <th width='80px;'>
                Giá
                </th>
                   <th>
                   Loại hình trả góp
                   </th>
                <th width='100px;'>
                Tỉ lệ trả trước
                </th>
                <th width='100px;'>
                Số tiền trả trước
                </th>
                <th width='90px;'>
                Số tháng góp
                </th>
                <th width='100px;'>
                Số tiền góp hàng tháng
                </th>
            </tr>
            <tr>
                <td>
                <a target="_blank" href='LINK' style='cursor:pointer;'><?php echo $product['product']['name']?></a>
                </td>
                <td>
                <img src='http://static.dienmaycholon.vn/product/product<?php echo $product['product']['myid']?>/product_<?php echo $product['product']['myid']?>_1.png' style='width:60px; height:60px;' />
                </td>
                <td>
            	   <?php echo $product['product']['discount']?>
                </td>
               <td>
             TYPE
               </td>
                <td>
               1
                </td>
                 <td>
                SALE
                </td>
                <td>
                TIME
                </td>
                <td>
               PERMONTH
                </td>
            </tr>
        </table>