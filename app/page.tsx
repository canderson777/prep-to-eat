import { Header } from "@/components/header"
import { Hero } from "@/components/hero"
import { AiRecipeDashboard } from "@/components/ai-recipe-dashboard"
import { Features } from "@/components/features"
import { Footer } from "@/components/footer"

export default function Home() {
  return (
    <div className="min-h-screen flex flex-col">
      <Header />
      <main className="flex-1">
        <Hero />

        <section className="py-12 md:py-16 bg-background">
          <div className="container px-4 md:px-6">
            <div className="max-w-3xl mx-auto">
              <div className="text-center mb-8">
                <h2 className="text-3xl md:text-4xl font-bold text-foreground mb-3">Try Our AI Recipe Generator</h2>
                <p className="text-lg text-muted-foreground">
                  Get started with 3 free recipe generations - no account needed!
                </p>
              </div>
              <AiRecipeDashboard />
            </div>
          </div>
        </section>

        <Features />
      </main>
      <Footer />
    </div>
  )
}
