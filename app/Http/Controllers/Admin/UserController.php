<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserController extends Controller
{
    public function adminUsers(Request $request)
    {
        $admin = auth('admin')->user();
        $search = trim((string) $request->query('q', ''));

        $users = Pengguna::query()
            ->when($search !== '', function ($query) use ($search) {
                $keyword = '%' . $search . '%';
                $query->where('nik', 'like', $keyword)
                    ->orWhere('nama_user', 'like', $keyword)
                    ->orWhere('email', 'like', $keyword)
                    ->orWhere('jabatan', 'like', $keyword);
            })
            ->orderBy('nama_user')
            ->paginate(10)
            ->withQueryString();

        $menuItems = $this->adminMenuItems('users');
        $pageTitle = 'Kelola Pengguna - ' . config('app.name', 'POLTREE');
        $topbarTitle = 'Pengguna';

        return view('dashboard.admin.users', compact('admin', 'users', 'search', 'menuItems', 'pageTitle', 'topbarTitle'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|unique:t_pengguna,nik',
            'nama_user' => 'required|string|max:255',
            'email' => 'required|email|unique:t_pengguna,email',
            'jabatan' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        Pengguna::create([
            'nik' => $request->nik,
            'nama_user' => $request->nama_user,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $file = $request->file('excel_file');
        
        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            if (count($rows) <= 1) {
                return back()->with('error', 'File Excel kosong atau hanya berisi header.');
            }

            $headers = array_map(function($val) {
                return strtolower(trim((string)$val));
            }, $rows[0]);

            $nikIndex = array_search('nik', $headers);
            $namaIndex = array_search('nama', $headers);
            if ($namaIndex === false) {
                $namaIndex = array_search('nama lengkap', $headers);
            }
            if ($namaIndex === false) {
                $namaIndex = array_search('nama_user', $headers);
            }
            $emailIndex = array_search('email', $headers);
            $jabatanIndex = array_search('jabatan', $headers);
            if ($jabatanIndex === false) {
                $jabatanIndex = array_search('role', $headers);
            }
            $passwordIndex = array_search('password', $headers);

            if ($nikIndex === false || $namaIndex === false || $emailIndex === false || $jabatanIndex === false) {
                $nikIndex = 0;
                $namaIndex = 1;
                $emailIndex = 2;
                $jabatanIndex = 3;
                $passwordIndex = 4;
            }

            $successCount = 0;
            $failCount = 0;

            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                if (empty(array_filter($row))) {
                    continue; 
                }

                $nik = trim((string)($row[$nikIndex] ?? ''));
                $nama = trim((string)($row[$namaIndex] ?? ''));
                $email = trim((string)($row[$emailIndex] ?? ''));
                $jabatan = trim((string)($row[$jabatanIndex] ?? ''));
                $password = trim((string)($row[$passwordIndex] ?? ''));

                if (empty($nik) || empty($nama) || empty($email) || empty($jabatan)) {
                    $failCount++;
                    continue;
                }

                $existNik = Pengguna::where('nik', $nik)->exists();
                $existEmail = Pengguna::where('email', $email)->exists();

                if ($existNik || $existEmail) {
                    $failCount++;
                    continue;
                }

                if (empty($password)) {
                    $password = 'poltree123';
                }

                Pengguna::create([
                    'nik' => (int) $nik,
                    'nama_user' => $nama,
                    'email' => $email,
                    'jabatan' => $jabatan,
                    'password' => Hash::make($password),
                ]);

                $successCount++;
            }

            if ($failCount > 0) {
                return back()->with('success', "Berhasil mengimpor {$successCount} pengguna. {$failCount} pengguna gagal diimpor karena data tidak lengkap, NIK/Email ganda.");
            }

            return back()->with('success', "Seluruh data ({$successCount} pengguna) berhasil diimpor.");

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set Headers
        $sheet->setCellValue('A1', 'NIK');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Jabatan');
        $sheet->setCellValue('E1', 'Password');
        
        // Add Example Row 1
        $sheet->setCellValue('A2', '123456');
        $sheet->setCellValue('B2', 'Ahmad Thohari');
        $sheet->setCellValue('C2', 'ahmad@polibatam.ac.id');
        $sheet->setCellValue('D2', 'Dosen');
        $sheet->setCellValue('E2', 'poltree123');
        
        // Add Example Row 2
        $sheet->setCellValue('A3', '654321');
        $sheet->setCellValue('B3', 'Dede Nurdiansyah');
        $sheet->setCellValue('C3', 'dede@polibatam.ac.id');
        $sheet->setCellValue('D3', 'Tata Usaha');
        $sheet->setCellValue('E3', ''); 

        // Set column widths automatically
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'template_impor_pengguna.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function updateUser(Request $request, $nik)
    {
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'email' => 'required|email|unique:t_pengguna,email,' . $nik . ',nik',
            'jabatan' => 'required|string',
            'password' => 'nullable|string|min:6',
        ]);

        $user = Pengguna::findOrFail($nik);
        $data = [
            'nama_user' => $request->nama_user,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function deleteUser($nik)
    {
        $user = Pengguna::findOrFail($nik);
        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    private function adminMenuItems(string $active): array
    {
        return [
            ['label' => 'Dashboard', 'href' => route('admin.dashboard'), 'icon' => 'home', 'active' => $active === 'dashboard'],
            ['label' => 'Semua Layanan', 'href' => route('admin.services'), 'icon' => 'sparkles', 'active' => $active === 'services'],
            ['label' => 'Kelola Pengguna', 'href' => route('admin.users'), 'icon' => 'user', 'active' => $active === 'users'],
            ['label' => 'Kelola Layanan', 'href' => route('admin.links'), 'icon' => 'chain', 'active' => $active === 'links'],
            ['label' => 'Kelola Kategori', 'href' => route('admin.categories'), 'icon' => 'folder', 'active' => $active === 'categories'],
            ['label' => 'Kelola Tag', 'href' => route('admin.tags'), 'icon' => 'tag', 'active' => $active === 'tags'],
        ];
    }
}
