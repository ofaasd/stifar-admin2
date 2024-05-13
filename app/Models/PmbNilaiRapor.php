<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PmbNilaiRapor
 * 
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_peserta
 * @property float|null $nilai_mtk_smt1
 * @property float|null $nilai_bing_smt1
 * @property float|null $nilai_kimia_smt1
 * @property float|null $nilai_biologi_smt1
 * @property float|null $nilai_fisika_smt1
 * @property string|null $file_smt1
 * @property float|null $nilai_mtk_smt2
 * @property float|null $nilai_bing_smt2
 * @property float|null $nilai_kimia_smt2
 * @property float|null $nilai_fisika_smt2
 * @property float|null $nilai_biologi_smt2
 * @property string|null $file_smt2
 * @property float|null $nilai_mtk_smt3
 * @property float|null $nilai_bing_smt3
 * @property float|null $nilai_kimia_smt3
 * @property float|null $nilai_fisika_smt3
 * @property float|null $nilai_biologi_smt3
 * @property string|null $file_smt3
 * @property float|null $nilai_mtk_smt4
 * @property float|null $nilai_bing_smt4
 * @property float|null $nilai_kimia_smt4
 * @property float|null $nilai_fisika_smt4
 * @property float|null $nilai_biologi_smt4
 * @property string|null $file_smt4
 * @property float|null $nilai_mtk_smt5
 * @property float|null $nilai_bing_smt5
 * @property float|null $nilai_kimia_smt5
 * @property float|null $nilai_fisika_smt5
 * @property float|null $nilai_biologi_smt5
 * @property string|null $file_smt5
 *
 * @package App\Models
 */
class PmbNilaiRapor extends Model
{
	protected $table = 'pmb_nilai_rapor';
	public $timestamps = false;

	protected $casts = [
		'id_user' => 'int',
		'id_peserta' => 'int',
		'nilai_mtk_smt1' => 'float',
		'nilai_bing_smt1' => 'float',
		'nilai_kimia_smt1' => 'float',
		'nilai_biologi_smt1' => 'float',
		'nilai_fisika_smt1' => 'float',
		'nilai_mtk_smt2' => 'float',
		'nilai_bing_smt2' => 'float',
		'nilai_kimia_smt2' => 'float',
		'nilai_fisika_smt2' => 'float',
		'nilai_biologi_smt2' => 'float',
		'nilai_mtk_smt3' => 'float',
		'nilai_bing_smt3' => 'float',
		'nilai_kimia_smt3' => 'float',
		'nilai_fisika_smt3' => 'float',
		'nilai_biologi_smt3' => 'float',
		'nilai_mtk_smt4' => 'float',
		'nilai_bing_smt4' => 'float',
		'nilai_kimia_smt4' => 'float',
		'nilai_fisika_smt4' => 'float',
		'nilai_biologi_smt4' => 'float',
		'nilai_mtk_smt5' => 'float',
		'nilai_bing_smt5' => 'float',
		'nilai_kimia_smt5' => 'float',
		'nilai_fisika_smt5' => 'float',
		'nilai_biologi_smt5' => 'float'
	];

	protected $fillable = [
		'id_user',
		'id_peserta',
		'nilai_mtk_smt1',
		'nilai_bing_smt1',
		'nilai_kimia_smt1',
		'nilai_biologi_smt1',
		'nilai_fisika_smt1',
		'file_smt1',
		'nilai_mtk_smt2',
		'nilai_bing_smt2',
		'nilai_kimia_smt2',
		'nilai_fisika_smt2',
		'nilai_biologi_smt2',
		'file_smt2',
		'nilai_mtk_smt3',
		'nilai_bing_smt3',
		'nilai_kimia_smt3',
		'nilai_fisika_smt3',
		'nilai_biologi_smt3',
		'file_smt3',
		'nilai_mtk_smt4',
		'nilai_bing_smt4',
		'nilai_kimia_smt4',
		'nilai_fisika_smt4',
		'nilai_biologi_smt4',
		'file_smt4',
		'nilai_mtk_smt5',
		'nilai_bing_smt5',
		'nilai_kimia_smt5',
		'nilai_fisika_smt5',
		'nilai_biologi_smt5',
		'file_smt5'
	];
}
