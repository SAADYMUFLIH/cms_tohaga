<?php if ($this->input->get('q') != '1') {
?>
<?php }
?>
<div class='row'>
    <div class="col-md-6 col-xl-4">
        <div class="card mb-3 widget-content bg-midnight-bloom">
            <div class="widget-content-wrapper text-white">
                <div class="widget-content-left">
                    <div class="widget-heading">Jumlah User</div>
                    <!-- <div class="widget-subheading">Last year expenses</div> -->
                </div>
                <div class="widget-content-right">
                    <div class="widget-numbers text-white"><span><?= $total_user->total ?></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card mb-3 widget-content bg-arielle-smile">
            <div class="widget-content-wrapper text-white">
                <div class="widget-content-left">
                    <div class="widget-heading">Jumlah UMKM</div>
                    <!-- <div class="widget-subheading">Total Clients Profit</div> -->
                </div>
                <div class="widget-content-right">
                    <div class="widget-numbers text-white"><span><?= isset($total_umkm->total) ? $total_umkm->total : 0 ?></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card mb-3 widget-content bg-grow-early">
            <div class="widget-content-wrapper text-white">
                <div class="widget-content-left">
                    <div class="widget-heading">Jumlah Produk</div>
                    <!-- <div class="widget-subheading">People Interested</div> -->
                </div>
                <div class="widget-content-right">
                    <div class="widget-numbers text-white"><span><?= $total_produk->total ?></span></div>
                </div>
            </div>
        </div>
    </div>


    <!-- fix for small devices only -->
    <div class='clearfix hidden-md-up'></div>

</div>
<div class="mb-3" style="font-size: 16px"><b>UMKM</b></div>
<div class="row">
    <div class="col-6">
        <label>Tampil Berdasarkan</label>
        <!-- <select data-placeholder="Silahkan Pilih" id="tampil" name="tampil" multiple required="" class="form-control chosen-select"> -->
        <select data-placeholder="Silahkan Pilih" id="tampil" name="tampil" required="" class="form-control chosen-select">
            <option value=""></option>
            <option value="wil">Wilayah</option>
            <option value="Produk">Kategori Produk</option>
            <!-- <option value="bb">Bahan Baku</option> -->
            <!-- <option value="Workshop">Workshop</option>
            <option value="Kapasitas Produksi">Kapasitas Produksi</option>
            <option value="Penghargaan">Penghargaan</option>
            <option value="Sertifikat">Sertifikat</option> -->
        </select>
    </div>
    <div class="col-6">
        <label>Chart</label>
        <!-- <select data-placeholder="Silahkan Pilih" id="tampil" name="tampil" multiple required="" class="form-control chosen-select"> -->
        <select data-placeholder="Silahkan Pilih" id="chart" name="chart" required="" class="form-control chosen-select">
            <option value="bar">Bar</option>
            <option value="line">Line</option>
        </select>
    </div>
</div>
<div class="row mt-3 child" style="display: none;" id="wil">
    <div class="col-6 col-lg-3">
        <label>Provinsi</label>
        <?php echo $cbo_provinsi ?>
        <!-- <select data-placeholder="Silahkan Pilih" id="prov" name="prov" required="" class="form-control chosen-select">
            <option value=""></option>
            <option value="Wilayah">Provinsi</option>
        </select> -->
    </div>
    <div class="col-6 col-lg-3">
        <label>Kota/Kab</label>
        <?php echo $cbo_kota ?>
        <!-- <select data-placeholder="Silahkan Pilih" id="kota" name="kota" required="" class="form-control chosen-select">
            <option value=""></option>
            <option value="Wilayah">Kota/Kab</option>
        </select> -->
    </div>
    <div class="col-6 col-lg-3">
        <label>Kecamatan</label>
        <?php echo $cbo_kec ?>
        <!-- <select data-placeholder="Silahkan Pilih" id="kec" name="kec" required="" class="form-control chosen-select">
            <option value=""></option>
            <option value="Wilayah">Kecamatan</option>
        </select> -->
    </div>
    <div class="col-6 col-lg-3">
        <label>Kelurahan</label>
        <?php echo $cbo_kel ?>
        <!-- <select data-placeholder="Silahkan Pilih" id="kel" name="kel" required="" class="form-control chosen-select">
            <option value=""></option>
            <option value="Wilayah">Kelurahan</option>
        </select> -->
    </div>
</div>
<div class="row mt-3 child" style="display: none;" id="Produk">
    <div class="col-12">
        <label>Kategori Produk</label>
        <?php echo $cbo_kat_prod ?>
        <!-- <select data-placeholder="Silahkan Pilih" id="kat" name="kat" multiple required="" class="form-control chosen-select">
            <option value=""></option>
            <option value="Wilayah">Wilayah</option>
        </select> -->
    </div>
</div>
<div class="row mt-3 child" style="display: none;" id="bb">
    <div class="col-12">
        <label>Bahan Baku</label>
        <?php echo $cbo_bb ?>
        <!-- <select data-placeholder="Silahkan Pilih" id="bb" name="bb" multiple required="" class="form-control chosen-select">
            <option value=""></option>
            <option value="Wilayah">Wilayah</option>
        </select> -->
    </div>
</div>
<div class="row">
    <div class="col-12 mt-3 text-right">
        <button type="button" onclick="showChart()" class="btn btn-warning"><i class="fas fa-chart-bar"></i> Show Chart</button>
        <!-- <button type="button" onclick="showTable()" class="btn btn-warning"><i class='fas fa-table'></i> Show Table</button> -->

    </div>
</div>
<!-- <div class="row mt-3">
    <div class="col-12">
        <label>Kapasitas Produksi</label>
        <select data-placeholder="Silahkan Pilih" id="kap" name="kap" required="" class="form-control chosen-select">
            <option value=""></option>
            <option value="1000">1-1000</option>
            <option value="5000">1001-5000</option>
            <option value="terbanyak">> 5000</option>
        </select>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12">
        <label>Lokasi workshop</label>
        <input type="text" id="workshop" name="workshop" class="form-control form-control-sm" placeholder="Lokasi Workshop">
    </div>
</div> -->
<script>
    $(document).ready(function() {
        init_grid_data_manipulation();
        $('.chosen-select', this).chosen({
            width: "100%"
        });
        url_kab = '<?php echo base_url() . $controller ?>/kabupatenbyprov_id'
        cascade_dropdown(url_kab, 'id_provinsi', 'id_kabupaten', false);

        url_kec = '<?php echo base_url() . $controller ?>/kecamatanbykab_id'
        cascade_dropdown(url_kec, 'id_kabupaten', 'id_kecamatan', false);

        url_kel = '<?php echo base_url() . $controller ?>/kelurahanbykec_id'
        cascade_dropdown(url_kel, 'id_kecamatan', 'id_kelurahan', false);

    });

    $('#tampil').on('change', function(change, deselected) {
        console.log(deselected);
        $(".child").hide();
        if (deselected.deselected) {
            $("#" + deselected.deselected).hide();
        }
        if (deselected.selected) {
            $("#" + deselected.selected).show();
        }

        showChart();

    });

    $('.chosen-select').on('change', function(change, deselected) {
        showChart();
    });

    function showChart() {
        var tampil = $('#tampil').val();
        var chart = $('#chart').val();
        var id_provinsi = $('#id_provinsi').val();
        var id_kabupaten = $('#id_kabupaten').val();
        var id_kecamatan = $('#id_kecamatan').val();
        var id_kelurahan = $('#id_kelurahan').val();
        var id_kat_prod = $('#id_kat_prod').val();
        
        if (tampil == "" || chart == "") {
            return;
        }
        // $('.loading').show();
        console.log(tampil, chart, id_provinsi, id_kabupaten, id_kecamatan, id_kelurahan);
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url() . $controller ?>/showChart',
            data: 'tampil=' + tampil + '&chart=' + chart + '&id_provinsi=' + id_provinsi + '&id_kabupaten=' + id_kabupaten + '&id_kecamatan=' + id_kecamatan + '&id_kelurahan=' + id_kelurahan + '&id_kat_prod=' + id_kat_prod,
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            },
            success: function(html) {
                console.log(JSON.parse(html));
                let dataFromDb = JSON.parse(html);
                // $('#postList').html(html);
                // $('.loading').hide();

                var label = [];
                var value = [];
                for (var i in dataFromDb) {
                    label.push(dataFromDb[i].nama);
                    value.push(dataFromDb[i].jml);
                }
                console.log($('#chart').val());
                var ctx = document.getElementById('myChart').getContext('2d');
                var ctx2 = document.getElementById('myChart2').getContext('2d');
                if ($('#chart').val() == 'line') {
                    $('#myChart2').hide();
                    $('#myChart').show();
                    var chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: label,
                            datasets: [{
                                    label: 'Data Statistik UMKM',
                                    // backgroundColor: '#2f8732',
                                    borderColor: '#F45D2F',
                                    data: value,
                                }

                            ]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        callback: function(value, index, values) {
                                            return value;
                                        }
                                    }
                                }]
                            },
                            tooltips: {
                                callbacks: {
                                    label(tooltipItem, data) {
                                        // Get the dataset label.
                                        const label = data.datasets[tooltipItem.datasetIndex].label;

                                        // Format the y-axis value.
                                        //   const value = IDR(tooltipItem.yLabel)//this.longValConverter(tooltipItem.yLabel);

                                        return `${label}:  ${tooltipItem.yLabel}`;
                                    },
                                },
                            },

                        }
                    });

                } else {
                    $('#myChart').hide();
                    $('#myChart2').show();
                    var chart = new Chart(ctx2, {
                        type: 'bar',
                        data: {
                            labels: label,
                            datasets: [{
                                    label: 'Data Statistik UMKM',
                                    backgroundColor: '#f5d9d0',
                                    borderColor: '#F45D2F',
                                    borderWidth: 1,
                                    data: value,
                                }

                            ]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        callback: function(value, index, values) {
                                            return value;
                                        }
                                    }
                                }]
                            },
                            tooltips: {
                                callbacks: {
                                    label(tooltipItem, data) {
                                        // Get the dataset label.
                                        const label = data.datasets[tooltipItem.datasetIndex].label;

                                        // Format the y-axis value.
                                        //   const value = IDR(tooltipItem.yLabel)//this.longValConverter(tooltipItem.yLabel);

                                        return `${label}:  ${tooltipItem.yLabel}`;
                                    },
                                },
                            },

                        }
                    });

                }
            }
        });
    }

    function showTable() {
        console.log('showTable')
    }
</script>
<div class="container" style="padding-top:50px;">
    <div class="col-md-12" style="background-color: white;">
        <?php
        // $mapDays1week = [];
        // $dateNow = date('Y-m-d');
        // $prevDays = 30; //
        // for ($i = $prevDays; $i >= 0; $i--) {
        //     if ($i == 0) {
        //         array_push($mapDays1week, new DateTime("$dateNow"));
        //     } else {
        //         array_push($mapDays1week, new DateTime("$dateNow - $i day"));
        //     }
        // }
        // 
        ?>
        <canvas id="myChart" style="height:500px !important;width:100% !important; display: none"></canvas>
        <canvas id="myChart2" style="height:500px !important;width:100% !important; display: none"></canvas>
    </div>

    <!-- <div class="col-md-6" style="padding-top:50px;">
        <canvas id="myChart2"></canvas>
    </div> -->
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script type="text/javascript">

</script>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script type="text/javascript">
    var ctx = document.getElementById('myChart').getContext('2d');
    var ctx2 = document.getElementById('myChart2').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                <?php
                // foreach ($mapDays1week as $data) {
                //     echo "'" . $data->format('d/m/y') . "',";
                // }
                ?>
            ],
            datasets: [{
                    label: 'Pasien Baru',
                    backgroundColor: '#2f8732',
                    // borderColor: '#93C3D2',
                    data: [
                        <?php
                        // if (count($graph_pasien_baru) > 0) {
                        //     foreach ($mapDays1week as $key => $value) {
                        //         $dt = false;
                        //         foreach ($graph_pasien_baru as $data) {
                        //             if ($data['create_at'] == $value->format('d/m/y')) {
                        //                 $dt = $data['count'] . ", ";
                        //             }
                        //         }

                        //         if ($dt) {
                        //             echo $dt;
                        //         } else {
                        //             echo '0,';
                        //         }
                        //     }
                        // }
                        ?>
                    ],
                },
                {
                    label: 'Pasien Lama',
                    backgroundColor: '#f5c45b',
                    // borderColor: '#8B008B',
                    data: [
                        <?php
                        // if (count($graph_pasien_lama) > 0) {
                        //     foreach ($mapDays1week as $key => $value) {
                        //         $dt = false;
                        //         foreach ($graph_pasien_lama as $data) {
                        //             if ($data['create_at'] == $value->format('d/m/y')) {
                        //                 $dt = $data['count'] . ", ";
                        //             }
                        //         }

                        //         if ($dt) {
                        //             echo $dt;
                        //         } else {
                        //             echo '0,';
                        //         }
                        //     }
                        // }
                        ?>
                    ]
                }
            ]
        },
        options: {
            elements: {
                line: {
                    tension: 0
                }
            },
            title: {
                display: true,
                text: 'Perbandingan Jumlah Kunjungan',
                fontSize: 20
            },
            scales: {
                yAxes: [{
                    ticks: {
                        // Include a dollar sign in the ticks
                        // callback: function(value, index, values) {
                        //     return value;
                        // }
                        precision: 0,
                        // beginAtZero:true
                    }
                }]
            }
        }
    });

    var chart = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: ['Sukses', 'Drop out', 'Kambuh', 'Rujuk'],
            datasets: [{
                backgroundColor: [
                    '#c2bd3c',
                    '#c23c3c',
                    '#93C3D2',
                    '#32a852'
                ],
                // borderColor: '#93C3D2',
                data: [
                    <?php
                    // foreach ($data_ubm as $value) {
                    //     echo "'" . $value . "',";
                    // }
                    ?>
                ]
            }, ]
        },
        options: {
            legend: {
                display: true,
                position: 'right',
                labels: {
                    generateLabels: function(chart) {
                        var data = chart.data;
                        if (data.labels.length && data.datasets.length) {
                            return data.labels.map(function(label, i) {
                                var meta = chart.getDatasetMeta(0);
                                var ds = data.datasets[0];
                                var arc = meta.data[i];
                                var custom = arc && arc.custom || {};
                                var getValueAtIndexOrDefault = Chart.helpers.getValueAtIndexOrDefault;
                                var arcOpts = chart.options.elements.arc;
                                var fill = custom.backgroundColor ? custom.backgroundColor : getValueAtIndexOrDefault(ds.backgroundColor, i, arcOpts.backgroundColor);
                                var stroke = custom.borderColor ? custom.borderColor : getValueAtIndexOrDefault(ds.borderColor, i, arcOpts.borderColor);
                                var bw = custom.borderWidth ? custom.borderWidth : getValueAtIndexOrDefault(ds.borderWidth, i, arcOpts.borderWidth);

                                // We get the value of the current label
                                var value = chart.config.data.datasets[arc._datasetIndex].data[arc._index];

                                return {
                                    // Instead of `text: label,`
                                    // We add the value to the string
                                    text: label + "   : " + value,
                                    fillStyle: fill,
                                    strokeStyle: stroke,
                                    lineWidth: bw,
                                    hidden: isNaN(ds.data[i]) || meta.data[i].hidden,
                                    index: i
                                };
                            });
                        } else {
                            return [];
                        }
                    }
                }
            },
            title: {
                display: true,
                text: 'Upaya Berhenti Merokok',
                fontSize: 20
            },
        }
    });
</script> -->
<!-- /.row -->