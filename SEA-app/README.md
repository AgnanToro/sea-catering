# SEA Catering - Healthy Meal Delivery Service

![SEA Catering Logo](https://img.shields.io/badge/SEA%20Catering-Healthy%20Meals%2C%20Anytime%2C%20Anywhere-teal)

SEA Catering adalah aplikasi web layanan pengiriman makanan sehat yang memungkinkan pelanggan untuk mengkustomisasi menu dan mendapatkan makanan bergizi melalui sistem langganan yang fleksibel.

## 🌟 Features

### Level 1: Homepage Statis
- ✅ Nama bisnis dan slogan "Healthy Meals, Anytime, Anywhere"
- ✅ Deskripsi layanan dan fitur utama
- ✅ Informasi kontak manajer (Brian) dan nomor telepon
- ✅ Design responsif dengan Tailwind CSS

### Level 2: Interaktivitas
- ✅ Navigasi responsif (Home, Menu, Subscription, Contact Us)
- ✅ Tampilan menu interaktif dengan modal detail
- ✅ Sistem testimoni dengan form rating
- ✅ Mobile-friendly dengan hamburger menu

### Level 3: Sistem Langganan
- ✅ Form langganan lengkap dengan validasi
- ✅ Pilihan paket (Diet/Protein/Royal)
- ✅ Seleksi jenis makanan (sarapan/siang/malam)
- ✅ Pemilihan hari pengiriman
- ✅ Input alergi makanan (opsional)
- ✅ Kalkulasi harga otomatis

### Level 4: Authentication (Basic Implementation)
- ✅ Form login dan registrasi
- ✅ Validasi input dan keamanan dasar
- ✅ Proteksi fitur langganan untuk user terautentikasi
- ✅ Session management sederhana

### Level 5: Dashboard (Ready for Implementation)
- 🔄 Dashboard user untuk mengelola langganan
- 🔄 Dashboard admin dengan statistik MRR
- 🔄 Manajemen data pelanggan dan pesanan

## 🚀 Tech Stack

- **Frontend**: React 18 + TypeScript
- **Build Tool**: Vite
- **Styling**: Tailwind CSS
- **Icons**: Font Awesome 6
- **Fonts**: Google Fonts (Inter)

## 📦 Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd SEA-app
```

2. Install dependencies:
```bash
npm install
```

3. Start development server:
```bash
npm run dev
```

4. Open your browser and navigate to `http://localhost:5173`

## 🛠️ Development

### Project Structure
```
SEA-app/
├── src/
│   ├── components/         # Reusable components
│   │   ├── Header.tsx
│   │   └── Footer.tsx
│   ├── pages/             # Page components
│   │   ├── Home.tsx
│   │   ├── Menu.tsx
│   │   ├── Subscription.tsx
│   │   └── Contact.tsx
│   ├── types/             # TypeScript type definitions
│   │   └── index.ts
│   ├── utils/             # Utility functions
│   │   └── helpers.ts
│   ├── App.tsx            # Main application component
│   ├── main.tsx          # Application entry point
│   └── index.css         # Global styles with Tailwind
├── public/               # Static assets
├── .github/             # GitHub specific files
│   └── copilot-instructions.md
└── package.json
```

### Available Scripts

- `npm run dev` - Start development server
- `npm run build` - Build for production
- `npm run preview` - Preview production build
- `npm run lint` - Run ESLint

## 🎨 Design System

### Colors
- **Primary**: Teal (#14b8a6, #0d9488)
- **Secondary**: Gray scale
- **Accent**: Yellow (#fbbf24) for highlights

### Components
- Responsive grid layouts
- Card-based design
- Consistent button styles
- Form validation with error states
- Modal overlays for detailed views

## 📱 Responsive Design

The application is fully responsive and optimized for:
- 📱 Mobile devices (320px+)
- 📱 Tablets (768px+)
- 💻 Desktop (1024px+)

## 🍽️ Menu Plans

### Paket Diet (Rp 75.000/hari)
- Makanan rendah kalori
- Porsi terkontrol
- Informasi nutrisi lengkap
- Konsultasi diet gratis

### Paket Protein (Rp 95.000/hari) - TERPOPULER
- Tinggi protein (30-35g per porsi)
- Ideal untuk pembentukan otot
- Karbohidrat kompleks
- Konsultasi nutrisi gratis

### Paket Royal (Rp 120.000/hari)
- Menu premium
- Porsi lebih besar
- Termasuk camilan sehat
- Konsultasi dengan ahli gizi

## 💰 Pricing Calculation

Harga langganan dihitung berdasarkan:
- Jenis paket yang dipilih
- Jumlah jenis makanan per hari
- Jumlah hari pengiriman per minggu

**Formula**: `Base Price × Meal Types × Delivery Days`

## 🔒 Security Features

- Input validation dan sanitization
- XSS prevention
- Phone number and email validation
- Form validation dengan error handling
- TypeScript untuk type safety

## 👥 Contact Information

**Manajer**: Brian  
**Telepon**: +62 812-3456-7890  
**Email**: info@seacatering.com  
**Alamat**: Jl. Sehat No. 123, Jakarta Selatan

## 🚀 Deployment

### Build for Production
```bash
npm run build
```

### Deploy to Vercel
```bash
npm install -g vercel
vercel --prod
```

### Deploy to Netlify
```bash
npm run build
# Upload dist/ folder to Netlify
```

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Design inspiration from HelloFresh
- Icons by Font Awesome
- Images from Unsplash
- Built with ❤️ for healthy living

---

**SEA Catering** - *Healthy Meals, Anytime, Anywhere* 🥗✨
