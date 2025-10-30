import Link from "next/link"

export function Footer() {
  return (
    <footer className="border-t bg-muted/30">
      <div className="container px-4 md:px-6 py-12">
        <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
          <div>
            <div className="flex items-center gap-2 mb-4">
              <div className="flex items-center justify-center w-10 h-10 rounded-xl bg-primary">
                <span className="text-2xl text-primary-foreground">🥗</span>
              </div>
              <span className="text-xl font-bold text-foreground">Prep to Eat</span>
            </div>
            <p className="text-sm text-muted-foreground leading-relaxed">
              Making healthy eating simple, delicious, and accessible for everyone.
            </p>
          </div>
          <div>
            <h3 className="font-semibold text-foreground mb-4">Recipes</h3>
            <ul className="space-y-3 text-sm">
              <li>
                <Link href="/recipes" className="text-muted-foreground hover:text-primary transition-colors">
                  All Recipes
                </Link>
              </li>
              <li>
                <Link href="/recipes/breakfast" className="text-muted-foreground hover:text-primary transition-colors">
                  Breakfast
                </Link>
              </li>
              <li>
                <Link href="/recipes/lunch" className="text-muted-foreground hover:text-primary transition-colors">
                  Lunch
                </Link>
              </li>
              <li>
                <Link href="/recipes/dinner" className="text-muted-foreground hover:text-primary transition-colors">
                  Dinner
                </Link>
              </li>
            </ul>
          </div>
          <div>
            <h3 className="font-semibold text-foreground mb-4">Resources</h3>
            <ul className="space-y-3 text-sm">
              <li>
                <Link href="/meal-plans" className="text-muted-foreground hover:text-primary transition-colors">
                  Meal Plans
                </Link>
              </li>
              <li>
                <Link href="/nutrition" className="text-muted-foreground hover:text-primary transition-colors">
                  Nutrition Guide
                </Link>
              </li>
              <li>
                <Link href="/blog" className="text-muted-foreground hover:text-primary transition-colors">
                  Blog
                </Link>
              </li>
              <li>
                <Link href="/faq" className="text-muted-foreground hover:text-primary transition-colors">
                  FAQ
                </Link>
              </li>
            </ul>
          </div>
          <div>
            <h3 className="font-semibold text-foreground mb-4">Company</h3>
            <ul className="space-y-3 text-sm">
              <li>
                <Link href="/about" className="text-muted-foreground hover:text-primary transition-colors">
                  About Us
                </Link>
              </li>
              <li>
                <Link href="/contact" className="text-muted-foreground hover:text-primary transition-colors">
                  Contact
                </Link>
              </li>
              <li>
                <Link href="/privacy" className="text-muted-foreground hover:text-primary transition-colors">
                  Privacy Policy
                </Link>
              </li>
              <li>
                <Link href="/terms" className="text-muted-foreground hover:text-primary transition-colors">
                  Terms of Service
                </Link>
              </li>
            </ul>
          </div>
        </div>
        <div className="mt-12 pt-8 border-t text-center text-sm text-muted-foreground">
          <p>&copy; {new Date().getFullYear()} Prep to Eat. All rights reserved.</p>
        </div>
      </div>
    </footer>
  )
}
