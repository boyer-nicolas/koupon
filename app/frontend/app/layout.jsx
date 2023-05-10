import './assets/styles/app.scss'
import Footer from './components/Footer'
import Drawer from './Components/Drawer'
import Navbar from './Components/Navbar'

export const metadata = {
  title: 'Koupon - Buy, Apply, Save.',
  description: 'Buy, Apply, Save.',
}

export default function RootLayout({ children })
{
  return (
    <html lang="en">
      <body>
        <Drawer>
          <Navbar />
          {children}
          <Footer />
        </Drawer>
      </body>
    </html>
  )
}
