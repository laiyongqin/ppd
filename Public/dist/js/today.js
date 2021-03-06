$(function () {
    $.getJSON("../api/ppdname",function(result){
        if(result.status == 1){
            $(".ppd-name").html(result.name);
        }else{
            $(".ppd-name").html("当前未绑定账户");
        }
    });
    // $.getJSON("../api/ppdbalance",function(result){
    //     if(result.Balance){
    //         $(".ppd-balance").html(result.Balance[4].Balance);
    //     }else{
    //         $(".ppd-name").html("当前未绑定账户");
    //     }
    // });
    $.getJSON("../api/ppdbalance",function(result){
        if(result.Balance){
            html = '';
            color = ['green','blue','orange','red','purple','grey','black','blue','green','yellow','red','orange'];
            $.each(result.Balance, function(i, item){
                if(item.AccountCategory.indexOf("现金余额")!=-1){
                    category = item.AccountCategory.replace('.', "<br/>").replace('用户', "拍拍贷用户");
                    html = html + '<div class="col-md-3 col-sm-6 col-xs-12"><div class="info-box"><span class="info-box-icon bg-' + color[i] +'"><i class="fa fa-cny"></i></span><div class="info-box-content"><span class="info-box-text">' + category + '</span><span class="info-box-number ppd-balance">' + item.Balance + '</span></div></div></div>';
                }
            });
            $(".info-balance").html(html);
        }
    });

    $.getJSON("../api/historylist",function(result){
        if(result.Result == 0){
            var str = '';
            if(result.TotalRecord<=0){
                str = '<tr><td>当前用户暂无成交纪录</td><td></td><td></td><td></td><td></td><td></td></tr>';
            }else{
                $.each(result.BidList, function( index, item ) {
                    str = str + '<tr><td><a href="pages/examples/invoice.html">' + item.ListingId + '</a></td><td>' + item.Title + '</td><td><span class="label label-success">' + item.Months + '</span></td><td><span class="label label-warning">'+ item.Rate + '%</span></td><td><span class="label label-info">'+ item.Amount + '</span></td><td><span class="label label-info">'+ item.BidAmount + '</span></td></tr>';
                });
            }
        }else{
            str = '<tr><td>获取历史成交标失败，请检查授权情况</td><td></td><td></td><td></td><td></td><td></td></tr>';
        }
        $(".tb-history").html(str);

    });

});
