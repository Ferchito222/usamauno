<hr /> 
<div style="width: 100%; border: 0px solid #FFFF00; text-align:center;">
    <table cellpadding="0" cellspacing="0" border="0"
           style="width: 100%; height: auto; font-size: 80%; font-family: Arial;">
        <tr>
            <td style="width:34%;" align="left"><img src="{{asset($image1)}}" alt="" width="100" style="padding: 3px;"/></td>
            <td style="width:32%;"></td>
            <td style="width:34%;" align="right">
                @foreach($images as $rp)
                <img src="{{ asset($rp->companyProduct()->first()->company()->first()->image) }}" alt="" width="100" style="padding: 3px;"/>
                @endforeach
            </td>
        </tr>
    </table>
</div>