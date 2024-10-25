<td>
    @if($header->creditProduct != null && $header->creditProduct->slug === 'PMO')
        <a href="{{ route('certificate.show', [
            'code'      => 'de',
            'rp_id'     => encode($header->ad_retailer_product_id),
            'type'      => 'certificate',
            'header_id' => encode($header->id),
            'format'    => 'pdf',
        ]) }}"
           class="btn btn-info" style="font-size: 100%;" target="_blank">
            Ver Certificado de Emisión
        </a>
    @elseif($header->issued == true)
        <a href="{{ route('certificate.show', [
                    'code'      => $code,
                    'rp_id'     => encode($header->ad_retailer_product_id),
                    'type'      => 'certificate',
                    'header_id' => encode($header->id),
                    'format'    => 'pdf',
                ]) }}" class="btn btn-info" style="font-size: 100%;" target="_blank">
            Ver Certificado
        </a>
    @endif
</td>
<td>
        <a href="{{ route('dp.policy.edit', [
            'code'      => $code,
            'rp_id'     => $rp_id,
            'header_id' => encode($header->id),
        ]) }}" class="btn btn-success" style="font-size: 100%;">Editar</a>
    
</td>