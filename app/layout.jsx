import './assets/styles/app.scss'
import Footer from './Components/Footer'
import Drawer from './Components/Drawer'
import Navbar from './Components/Navbar'
import { Toaster } from 'react-hot-toast'
export const metadata = {
  title: 'Koupon - Buy, Apply, Save.',
  description: 'Buy, Apply, Save.',
}
import Progress from './Components/Progress'

export default function RootLayout({ children })
{
  return (
    <html lang="en">
      <body>
        <Drawer>
          <Progress />
          <Navbar />
          <main id="koupon pt-50">
            {children}
          </main>
          <Footer />
          <Toaster
            position="top-center"
            reverseOrder={false}
          />
        </Drawer>
      </body>
    </html>
  )
}
