import { Header } from "@/components/header"
import { RecipeGrid } from "@/components/recipe-grid"
import { RecipeFilters } from "@/components/recipe-filters"
import { Footer } from "@/components/footer"

export default function RecipesPage() {
  return (
    <div className="min-h-screen flex flex-col">
      <Header />
      <main className="flex-1">
        <div className="bg-primary py-12 md:py-16">
          <div className="container px-4 md:px-6">
            <h1 className="text-3xl md:text-5xl font-bold text-primary-foreground text-balance">Healthy Recipes</h1>
            <p className="mt-4 text-lg md:text-xl text-primary-foreground/90 max-w-2xl text-pretty">
              Discover delicious, nutritious recipes that make meal prep easy and enjoyable
            </p>
          </div>
        </div>
        <div className="container px-4 md:px-6 py-8 md:py-12">
          <RecipeFilters />
          <RecipeGrid />
        </div>
      </main>
      <Footer />
    </div>
  )
}
